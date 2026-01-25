<?php

namespace App\Service;

use App\Entity\Url;
use Doctrine\ORM\EntityManagerInterface;

class UrlService
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function create(array $data): Url
    {
        $alias = $data['alias'] ?? null;

        if ($alias) {
            $exists = $this->em->getRepository(Url::class)
                ->findOneBy(['short_code' => $alias]);

            if ($exists) {
                throw new \InvalidArgumentException('Alias already exists');
            }
        } else {
            do {
                $alias = bin2hex(random_bytes(4));
                $exists = $this->em->getRepository(Url::class)
                    ->findOneBy(['short_code' => $alias]);
            } while ($exists);
        }

        $url = new Url();
        $url->setSession($data['session']);
        $url->setOriginalUrl($data['url']);
        $url->setShortCode($alias);
        $url->setIsPublic($data['isPublic'] ?? false);
        $url->setExpiresAt($data['expiresAt'] ?? null);

        $this->em->persist($url);
        $this->em->flush();

        return $url;
    }


    public function delete(Url $url): void
    {
        $url->setDeletedAt(new \DateTimeImmutable());
        $this->em->flush();
    }

    public function resolveShortCode(string $shortCode): Url
    {
        $url = $this->em->getRepository(Url::class)->findOneBy([
            'short_code' => $shortCode,
            'deleted_at' => null,
        ]);

        if (!$url) {
            throw new \RuntimeException('Not 111found');
        }
//TODO
//        if (
//            $url->getExpiresAt() !== null &&
//            $url->getExpiresAt() < new \DateTimeImmutable()
//        ) {
//            throw new \RuntimeException('Expired');
//        }

        return $url;
    }

}
