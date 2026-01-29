"use client";

import { useEffect, useState } from "react";

interface Url {
    id: number;
    originalUrl: string;
    shortCode: string;
    isPublic: boolean;
    expiresAt: string;
}

export function UrlTable() {
    const [urls, setUrls] = useState<Url[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        async function fetchUrls() {
            try {
                const res = await fetch(
                    "http://host.docker.internal:57000/api/public",
                    {
                        credentials: "include",
                        method: "GET"
                    }
                );

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}`);
                }

                const data = await res.json();
                setUrls(data);
            } catch (e: any) {
                setError(e.message);
            } finally {
                setLoading(false);
            }
        }

        fetchUrls();
    }, []);

    if (loading) return <p className="mt-6">Loading URLs…</p>;
    if (error) return <p className="mt-6 text-red-600">Error: {error}</p>;

    return (
        <div className="mt-8 overflow-x-auto">
            <table className="min-w-full border border-gray-200 rounded-lg">
                <thead className="bg-gray-100">
                <tr>
                    <th className="px-4 py-2 text-left text-sm font-semibold">ID</th>
                    <th className="px-4 py-2 text-left text-sm font-semibold">
                        Original URL
                    </th>
                    <th className="px-4 py-2 text-left text-sm font-semibold">
                        Short code
                    </th>
                    <th className="px-4 py-2 text-left text-sm font-semibold">
                        Public
                    </th>
                    <th className="px-4 py-2 text-left text-sm font-semibold">
                        Expires at
                    </th>
                </tr>
                </thead>
                <tbody>
                {urls.map((url) => (
                    <tr
                        key={url.id}
                        className="border-t hover:bg-gray-50 transition"
                    >
                        <td className="px-4 py-2 text-sm">{url.id}</td>
                        <td className="px-4 py-2 text-sm max-w-md truncate">
                            <a
                                href={url.originalUrl}
                                target="_blank"
                                className="text-blue-600 hover:underline"
                            >
                                {url.originalUrl}
                            </a>
                        </td>
                        <td className="px-4 py-2 text-sm font-mono">
                            {url.shortCode}
                        </td>
                        <td className="px-4 py-2 text-sm">
                            {url.isPublic ? "Yes" : "No"}
                        </td>
                        <td className="px-4 py-2 text-sm">
                            {new Date(url.expiresAt).toLocaleString()}
                        </td>
                    </tr>
                ))}

                {urls.length === 0 && (
                    <tr>
                        <td
                            colSpan={5}
                            className="px-4 py-6 text-center text-gray-500"
                        >
                            No public URLs
                        </td>
                    </tr>
                )}
                </tbody>
            </table>
        </div>
    );
}
