import { useState } from "react";
import { createSession } from "@/services/session.service";

export function useSession() {
    const [session, setSession] = useState(null);
    const [loading, setLoading] = useState(false);

    async function create() {
        setLoading(true);
        try {
            const data = await createSession();
            setSession(data);
        } finally {
            setLoading(false);
        }
    }

    return { session, loading, create };
}
