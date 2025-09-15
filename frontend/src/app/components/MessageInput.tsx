"use client";

import { useState } from "react";

export default function MessageInput() {
    const [message, setMessage] = useState("");

    const handleSend = () => {
        if (!message.trim()) return;
        console.log("Mensagem enviada:", message);
        setMessage("");
    };

    return (
        <div className="flex gap-2">
            <textarea
                className="flex-1 border rounded-lg px-3 py-2 text-gray-600 leading-none"
                placeholder="Digite sua mensagem..."
                value={message}
                onChange={(e) => setMessage(e.target.value)}
            />
            <button
                onClick={handleSend}
                className="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
            >
                Enviar
            </button>
        </div>
    );
}
