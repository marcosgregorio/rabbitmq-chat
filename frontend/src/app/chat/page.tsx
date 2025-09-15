import ChatBox from "../components/ChatBox";
import MessageInput from "../components/MessageInput";

export default function ChatPage() {
    return (
        <main className="flex min-h-screen flex-col items-center justify-between p-6 bg-gray-100">
            <h1 className="text-2xl font-bold text-blue-600 mb-4">Sala de Chat</h1>

            <div className="w-full max-w-lg flex flex-col bg-white shadow-lg rounded-2xl p-4">
                <ChatBox />
                <MessageInput />
            </div>
        </main>
    );
}
