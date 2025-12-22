const canvas = document.getElementById("water");
const ctx = canvas.getContext("2d");

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
window.addEventListener("resize", resizeCanvas);
resizeCanvas();

const particles = [];
const COUNT = 3000;
const mouse = { x: 0, y: 0, vx: 0, vy: 0 };

window.addEventListener("mousemove", e => {
    mouse.vx = e.clientX - mouse.x;
    mouse.vy = e.clientY - mouse.y;
    mouse.x = e.clientX;
    mouse.y = e.clientY;
});

// cria partículas
for (let i = 0; i < COUNT; i++) {
    const x = Math.random() * canvas.width;
    const y = Math.random() * canvas.height;

    particles.push({
        x,
        y,
        ox: x,
        oy: y,
        vx: 0,
        vy: 0
    });
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    for (const p of particles) {
        const dx = p.x - mouse.x;
        const dy = p.y - mouse.y;
        const dist = Math.sqrt(dx * dx + dy * dy) || 0.001;
        const radius = 80;

        if (dist < radius) {
            const force = (radius - dist) / radius;
            p.vx += (dx / dist) * force * 3;
            p.vy += (dy / dist) * force * 3;
        }

        // retorno suave
        p.vx += (p.ox - p.x) * 0.008;
        p.vy += (p.oy - p.y) * 0.008;

        // viscosidade
        p.vx *= 0.93;
        p.vy *= 0.93;

        p.x += p.vx;
        p.y += p.vy;

        ctx.beginPath();
        ctx.arc(p.x, p.y, 1.1, 0, Math.PI * 2);
        ctx.fillStyle = "rgba(120,180,255,0.7)";
        ctx.fill();
    }

    requestAnimationFrame(animate);
}

animate();
