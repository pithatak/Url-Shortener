import { apiFetch } from "@/lib/api";

export async function createSession() {
    const res = await apiFetch(
        "http://host.docker.internal:57000/api/session",
        { method: "POST" }
    );

    return res.json();
}
