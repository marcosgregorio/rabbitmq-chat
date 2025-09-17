"use client";
import { useState } from "react";
import ChatBox from "../components/ChatBox";
import MessageInput from "../components/MessageInput";

type IMessage = {
  id: number;
  user: string;
  text: string;
};
export default function ChatPage() {
  const [messages, setMessages] = useState<IMessage[]>([
    { id: 1, user: "Alice", text: "Olá 👋" },
    { id: 2, user: "Bob", text: "Oi, tudo bem?" },
  ]);

  function onChangeMessage(message: string) {
    console.log("Mensagem enviada:", message);

    const randomId = Math.floor(Math.random() * 1000);
    messages.push({ id: randomId, user: "Você", text: message });

    setMessages([...messages]);
  }

  return (
    <main className="flex min-h-screen flex-col items-center justify-between p-6 bg-gray-100">
      <h1 className="text-2xl font-bold text-blue-600 mb-4">Sala de Chat</h1>

      <div className="w-full max-w-lg flex flex-col bg-white shadow-lg rounded-2xl p-4">
        <ChatBox messages={messages} />
        <MessageInput onChangeMessage={onChangeMessage} />
      </div>
    </main>
  );
}
