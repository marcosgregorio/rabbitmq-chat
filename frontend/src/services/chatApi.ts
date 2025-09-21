import axios from "axios";

const API_BASE_URL = "http://localhost:8000";

const chatApi = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

export const sendMessage = async (user: string, text: string) => {
  try {
    const reponse = await chatApi.post("/messages", { user, text });
    return reponse.data;
  } catch (error) {
    console.error("Error sending message:", error);
    throw error;
  }
};
