importScripts('https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/12.1.0/firebase-messaging.js');

firebase.initializeApp({
  apiKey: "AIzaSyC4M8L64yyL4L9dMgk7FE8arnxPpFs5e7U",
  authDomain: "play-store-23575.firebaseapp.com",
  projectId: "play-store-23575",
  storageBucket: "play-store-23575.firebasestorage.app",
  messagingSenderId: "675120752187",
  appId: "1:675120752187:web:dadd99686f1ce235c2de1e",
  measurementId: "G-E8XLW4HXT3"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(payload=>{
  const title = payload.notification.title;
  const options = {
    body: payload.notification.body,
    icon: payload.notification.icon || '/data/images/pcl.jpg'
  };
  self.registration.showNotification(title, options);
});