"use client";

import { useRouter } from "next/navigation";

export default function Home() {
    const router = useRouter();

    return (
        <main className="flex min-h-screen flex-col items-center justify-center p-24 bg-gray-100">
            <div className="bg-white shadow-lg rounded-2xl p-10 text-center">
                <h1 className="text-3xl font-bold text-blue-600 mb-4">💬 Meu Chat</h1>
                <p className="text-gray-700">Clique no botão para entrar no chat.</p>
                <button
                    onClick={() => router.push("/chat")}
                    className="mt-6 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                >
                    Entrar no chat
                </button>
            </div>
        </main>
    );
}
