  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js";
  import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-auth.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyDS7Pp9ywPLlIwRQrTq4ggjYq57Qw64mmI",
    authDomain: "act-3-sso.firebaseapp.com",
    projectId: "act-3-sso",
    storageBucket: "act-3-sso.firebasestorage.app",
    messagingSenderId: "1065628084933",
    appId: "1:1065628084933:web:bcad16e5c47aa3ecb05eac",
    measurementId: "G-18ZY06JH8S"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  auth.languageCode = 'en';
  const provider = new GoogleAuthProvider();
  
  const login = document.getElementById('google-login-btn');
  login.addEventListener('click', () => {
    signInWithPopup(auth, provider)
      .then((result) => {
        const user = result.user;
      fetch('verify_firebase_token.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email: user.email,
          name: user.displayName,
          firebase_uid: user.uid,
          id_token: user.accessToken
        })
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          window.location.href = 'index.php';
        }
      });
      }).catch((error) => {
        const errorCode = error.code;
        const errorMessage = error.message;
        const email = error.customData.email;
        const credential = GoogleAuthProvider.credentialFromError(error);
      });
    });

    