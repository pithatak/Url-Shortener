const express = require('express');
const path = require('path');

const app = express();
const PORT = 3000;

// Статичні файли
app.use(express.static(path.join(__dirname, 'public')));

// Catch-all middleware для SPA
app.use((req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(PORT, () => {
    console.log(`Frontend server running at http://localhost:${PORT}`);
});
