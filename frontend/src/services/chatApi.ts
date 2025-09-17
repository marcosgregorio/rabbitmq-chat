import axios from "axios";

const API_BASE_URL = "http://localhost:8000/api";

const chatApi = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

export const sendMessage = async (user: string, message: string) => {
  try {
    const reponse = await chatApi.post("/messages", { message });
    return reponse.data;
  } catch (error) {
    console.error("Error sending message:", error);
    throw error;
  }
};
