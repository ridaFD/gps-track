# 🔧 Frontend Environment Setup

Create a `.env` file in the root of your frontend project with these variables:

## For Pusher Cloud (Recommended)

```env
# Backend API URL
REACT_APP_API_URL=http://localhost:8000/api/v1

# Pusher Configuration
REACT_APP_PUSHER_KEY=your_pusher_app_key_here
REACT_APP_PUSHER_CLUSTER=us2
REACT_APP_PUSHER_HOST=
REACT_APP_PUSHER_PORT=443
REACT_APP_PUSHER_SCHEME=https
```

## For Soketi (Local WebSocket Server)

```env
# Backend API URL
REACT_APP_API_URL=http://localhost:8000/api/v1

# Soketi Configuration
REACT_APP_PUSHER_KEY=app-key
REACT_APP_PUSHER_CLUSTER=mt1
REACT_APP_PUSHER_HOST=127.0.0.1
REACT_APP_PUSHER_PORT=6001
REACT_APP_PUSHER_SCHEME=http
```

---

## 📝 Quick Setup

1. Copy the appropriate configuration above
2. Create `.env` file in `/Users/ridafakherlden/www/gps-track/`
3. Paste the configuration
4. Update `REACT_APP_PUSHER_KEY` with your actual key
5. Restart your frontend: `npm start`

---

## ✅ Verify

After creating `.env`:
- Restart frontend (stop with Ctrl+C, then `npm start`)
- Open browser console (F12)
- You should see "Pusher connected" message

---

## 🔑 Get Pusher Credentials

1. Sign up: https://pusher.com/
2. Create an app
3. Copy: App ID, Key, Secret, Cluster
4. Use Key in frontend `.env`
5. Use all credentials in backend `.env`

