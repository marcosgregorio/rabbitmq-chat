"use client";

import Message from "./Message";
import { useState } from "react";

export default function ChatBox() {
    const [messages] = useState([
        { id: 1, user: "Alice", text: "Olá 👋" },
        { id: 2, user: "Bob", text: "Oi, tudo bem?" },
    ]);

    return (
        <div className="flex-1 overflow-y-auto space-y-2 mb-4">
            {messages.map((msg) => (
                <Message key={msg.id} user={msg.user} text={msg.text} />
            ))}
        </div>
    );
}
