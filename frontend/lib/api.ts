export async function apiFetch(
    input: RequestInfo,
    init?: RequestInit
) {
    const res = await fetch(input, {
        ...init,
        credentials: "include",
        headers: {
            "Content-Type": "application/json",
            ...init?.headers,
        },
    });

    if (!res.ok) {
        throw new Error(`API error ${res.status}`);
    }

    return res;
}
