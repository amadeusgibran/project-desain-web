import './bootstrap';
import * as THREE from 'three';

const canvas = document.querySelector('#character-canvas');

if (canvas) {
    const stage = canvas.parentElement;
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(36, 1, 0.1, 100);
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, preserveDrawingBuffer: true });
    const character = new THREE.Group();
    const pointer = { active: false, x: 0 };

    camera.position.set(0, 1.2, 7);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const silver = new THREE.MeshStandardMaterial({
        color: 0x5f6765,
        metalness: 0.86,
        roughness: 0.28,
    });

    const darkSilver = new THREE.MeshStandardMaterial({
        color: 0x252827,
        metalness: 0.75,
        roughness: 0.34,
    });

    const accent = new THREE.MeshStandardMaterial({
        color: 0x0f685a,
        metalness: 0.58,
        roughness: 0.22,
    });

    const addMesh = (geometry, material, position, scale = [1, 1, 1], rotation = [0, 0, 0]) => {
        const mesh = new THREE.Mesh(geometry, material);
        mesh.position.set(...position);
        mesh.scale.set(...scale);
        mesh.rotation.set(...rotation);
        character.add(mesh);
        return mesh;
    };

    addMesh(new THREE.SphereGeometry(0.46, 48, 32), silver, [0, 1.92, 0], [0.9, 1.08, 0.82]);
    addMesh(new THREE.CapsuleGeometry(0.56, 1.26, 24, 48), silver, [0, 0.78, 0], [1, 1.02, 0.72]);
    addMesh(new THREE.TorusGeometry(0.5, 0.035, 12, 72), accent, [0, 1.4, 0.02], [1, 0.35, 1], [0.05, 0, 0]);
    addMesh(new THREE.CapsuleGeometry(0.13, 1.15, 16, 32), silver, [-0.7, 0.72, 0], [1, 1, 1], [0.18, 0, 0.42]);
    addMesh(new THREE.CapsuleGeometry(0.13, 1.15, 16, 32), silver, [0.7, 0.72, 0], [1, 1, 1], [0.18, 0, -0.42]);
    addMesh(new THREE.CapsuleGeometry(0.15, 1.3, 16, 32), darkSilver, [-0.34, -0.52, 0.05], [1, 1, 1], [0, 0, 0.14]);
    addMesh(new THREE.CapsuleGeometry(0.15, 1.3, 16, 32), darkSilver, [0.34, -0.52, 0.05], [1, 1, 1], [0, 0, -0.14]);
    addMesh(new THREE.TorusGeometry(1.2, 0.075, 18, 96), darkSilver, [0, -1.15, 0], [1, 0.28, 1], [0.1, 0, 0]);

    character.rotation.y = -0.36;
    scene.add(character);

    const key = new THREE.DirectionalLight(0xffffff, 3.2);
    key.position.set(3, 5, 4);
    scene.add(key);
    scene.add(new THREE.AmbientLight(0xd8fffa, 1.4));

    const resize = () => {
        const bounds = stage.getBoundingClientRect();
        renderer.setSize(bounds.width, bounds.height, false);
        camera.aspect = bounds.width / bounds.height;
        camera.updateProjectionMatrix();
    };

    stage.addEventListener('pointerdown', (event) => {
        pointer.active = true;
        pointer.x = event.clientX;
        stage.setPointerCapture(event.pointerId);
    });

    stage.addEventListener('pointermove', (event) => {
        if (!pointer.active) return;
        const delta = event.clientX - pointer.x;
        pointer.x = event.clientX;
        character.rotation.y += delta * 0.01;
    });

    stage.addEventListener('pointerup', () => {
        pointer.active = false;
    });

    const tick = () => {
        if (!pointer.active) {
            character.rotation.y += 0.006;
        }
        character.rotation.x = Math.sin(performance.now() * 0.001) * 0.04;
        renderer.render(scene, camera);
        requestAnimationFrame(tick);
    };

    resize();
    window.addEventListener('resize', resize);
    tick();
}

const aiToggle = document.querySelector('[data-ai-toggle]');
const aiPanel = document.querySelector('[data-ai-panel]');
const aiRefresh = document.querySelector('[data-ai-refresh]');
const aiSummary = document.querySelector('[data-ai-summary]');
const aiSuggestion = document.querySelector('[data-ai-suggestion]');
const aiSource = document.querySelector('[data-ai-source]');

const loadProfileInsight = async () => {
    if (!aiSummary) return;

    aiSummary.textContent = 'Membaca konteks profile...';
    aiSuggestion.textContent = '';

    try {
        const response = await fetch('/profile/ai-insight', {
            headers: { Accept: 'application/json' },
        });
        const insight = await response.json();

        aiSource.textContent = insight.source;
        aiSummary.textContent = insight.summary;
        aiSuggestion.textContent = insight.suggestion;
    } catch {
        aiSource.textContent = 'Local fallback';
        aiSummary.textContent = 'Profile siap tampil sebagai creative developer dengan sentuhan 3D.';
        aiSuggestion.textContent = 'Cek koneksi dev server jika insight Laravel gagal dimuat.';
    }
};

if (aiToggle && aiPanel) {
    aiToggle.addEventListener('click', () => {
        aiPanel.classList.toggle('open');
        loadProfileInsight();
    });
}

if (aiRefresh) {
    aiRefresh.addEventListener('click', loadProfileInsight);
}
