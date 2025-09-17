type MessageProps = {
  user: string;
  text: string;
};

export default function Message({ user, text }: MessageProps) {
  return (
    <div className="flex gap-1 p-2 rounded-lg bg-gray-100">
      <strong className="text-blue-600">{user}:</strong>
      <span className={`text-gray-600`}>{text}</span>
    </div>
  );
}
