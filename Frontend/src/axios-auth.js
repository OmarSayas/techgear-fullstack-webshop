import axios from "axios";

const API_BASE_URL =
  import.meta.env.VITE_API_BASE_URL?.trim() || "http://localhost";

const instance = axios.create({
  baseURL: API_BASE_URL,
});

const refreshClient = axios.create({
  baseURL: API_BASE_URL,
});

function decodeToken(token) {
  try {
    const base64Url = token.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    const padded = base64.padEnd(base64.length + ((4 - (base64.length % 4)) % 4), "=");
    return JSON.parse(atob(padded));
  } catch (error) {
    console.warn("Failed to decode token:", error);
    return null;
  }
}

function clearSession() {
  localStorage.removeItem("token");
  localStorage.removeItem("refreshToken");
}

function isTokenExpired(token) {
  const payload = decodeToken(token);
  if (!payload?.exp) {
    return true;
  }

  const now = Math.floor(Date.now() / 1000);
  return payload.exp <= now;
}

instance.interceptors.request.use(async (config) => {
  let token = localStorage.getItem("token");

  if (token && isTokenExpired(token)) {
    const refreshToken = localStorage.getItem("refreshToken");
    const payload = decodeToken(token);

    try {
      const refreshResponse = await refreshClient.post("/users/refresh", {
        username: payload?.data?.username,
        refreshtoken: refreshToken,
      });

      const refreshedToken = refreshResponse.data?.token || refreshResponse.data;
      localStorage.setItem("token", refreshedToken);
      token = refreshedToken;
    } catch (error) {
      clearSession();
      if (window.location.pathname !== "/login") {
        window.location.href = "/login";
      }
      throw new axios.Cancel("Refresh token failed");
    }
  }

  if (token) {
    config.headers = config.headers || {};
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

export { API_BASE_URL };
export default instance;
