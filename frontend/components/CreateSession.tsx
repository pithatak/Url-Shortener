"use client";

import {useSession} from "@/hooks/useSession";

export function CreateSession() {
    const {session, loading, create} = useSession();

    return (
        <div className="flex items-center gap-4">
            <button
                onClick={create}
                disabled={loading}
                className="px-4 py-2 bg-blue-600 text-white rounded"
            >
                {loading ? "Creating..." : "Create session"}
            </button>

            {session && (
                <span className="text-sm text-gray-600">
          Created at {new Date(session.createdAt).toLocaleString()}
        </span>
            )}
        </div>
    );
}
