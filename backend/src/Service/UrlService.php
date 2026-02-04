<?php

namespace App\Service;

use App\Dto\UrlCreateData;
use App\Entity\Session;
use App\Entity\Url;
use App\Message\UrlClickedMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

class UrlService
{
    private const EXPIRE_MAP = [
        '1h' => '+1 hour',
        '1d' => '+1 day',
        '1t' => '+1 week',
    ];


    public function __construct(private EntityManagerInterface $em, private MessageBusInterface $messageBus)
    {
    }

    public function create(UrlCreateData $data): Url
    {
        $alias = $data->alias;
        if ($alias) {
            if ($this->em->getRepository(Url::class)->findOneBy(['short_code' => $alias])) {
                throw new ConflictHttpException('Alias already exists');
            }
        } else {
            do {
                $alias = bin2hex(random_bytes(4));
            } while ($this->em->getRepository(Url::class)->findOneBy(['short_code' => $alias]));
        }

        $url = new Url();
        $url->setSession($data->session);
        $url->setOriginalUrl($data->url);
        $url->setShortCode($alias);
        $url->setIsPublic($data->isPublic);
        $url->setExpiresAt($this->resolveExpiresAt($data->expire));

        $this->em->persist($url);
        $this->em->flush();

        return $url;
    }

    public function resolveShortCode(string $shortCode): Url
    {
        $url = $this->em->getRepository(Url::class)->findOneBy([
            'short_code' => $shortCode,
            'deleted_at' => null,
        ]);

        if (!$url) {
            throw new NotFoundHttpException('Not found');
        }

        if ($url->getExpiresAt() !== null && $url->getExpiresAt() < new \DateTimeImmutable()) {
            throw new GoneHttpException('Expired url');
        }

        $this->messageBus->dispatch(
            new UrlClickedMessage(
                $url->getId(),
                new \DateTimeImmutable()
            )
        );

        return $url;
    }

    private function resolveExpiresAt(?string $expire): ?\DateTimeImmutable
    {
        if (!$expire) {
            return null;
        }

        if (!isset(self::EXPIRE_MAP[$expire])) {
            throw new BadRequestException('Invalid expire value');
        }

        return new \DateTimeImmutable(self::EXPIRE_MAP[$expire]);
    }

    public function deleteForSession(int $id, Session $session): void
    {
        $url = $this->em->getRepository(Url::class)->find($id);

        if (
            !$url ||
            $url->getSession()->getId() !== $session->getId()
        ) {
            throw new NotFoundHttpException('Not found');
        }

        $url->setDeletedAt(new \DateTimeImmutable());
        $this->em->flush();
    }
}
