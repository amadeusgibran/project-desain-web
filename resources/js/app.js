import './bootstrap';
import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

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

    // Load the GLTF/GLB Avatar model
    const loader = new GLTFLoader();
    const modelUrl = window.avatarModelUrl || '/3d/avatar.glb';

    loader.load(
        modelUrl,
        (gltf) => {
            const model = gltf.scene;

            // Auto-scale and center the avatar model so it fits the scene nicely
            const box = new THREE.Box3().setFromObject(model);
            const size = box.getSize(new THREE.Vector3());
            const center = box.getCenter(new THREE.Vector3());

            // Target height for the model inside our viewport (larger size)
            const targetHeight = 3.8;
            const scaleFactor = targetHeight / (size.y || 1);
            model.scale.set(scaleFactor, scaleFactor, scaleFactor);

            // Align bottom of the avatar (min.y) to a raised Y position (Y = -0.5)
            // Center the avatar along X and Z axes
            const avatarY = -0.5;
            model.position.set(
                -center.x * scaleFactor,
                avatarY - (box.min.y * scaleFactor),
                -center.z * scaleFactor
            );

            // Traverse the model to adjust material properties if necessary
            model.traverse((child) => {
                if (child.isMesh) {
                    if (child.material) {
                        child.material.depthWrite = true;
                        // Avoid complete roughness or metallic reflections drowning out colors
                        if (child.material.roughness !== undefined) {
                            child.material.roughness = Math.max(child.material.roughness, 0.25);
                        }
                    }
                }
            });

            character.add(model);

            // Mark stage as loaded to hide fallback background and make it transparent
            stage.classList.add('js-loaded');
        },
        undefined,
        (error) => {
            console.error('Error loading avatar GLB model:', error);
        }
    );

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
const aiMessages = document.querySelector('[data-ai-messages]');
const aiForm = document.querySelector('[data-ai-form]');
const aiInput = document.querySelector('[data-ai-input]');
const aiSend = document.querySelector('[data-ai-send]');
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const chatStorageKey = 'gibran-portfolio-chat';
const welcomeMessage = {
    role: 'assistant',
    content: 'Halo, saya bisa bantu menjelaskan portfolio, layanan, availability, dan cara kontak Gibran Amadeus.',
};

let chatHistory = [];
let isChatLoading = false;

const loadChatHistory = () => {
    try {
        const stored = JSON.parse(sessionStorage.getItem(chatStorageKey) || '[]');
        chatHistory = Array.isArray(stored) && stored.length ? stored : [welcomeMessage];
    } catch {
        chatHistory = [welcomeMessage];
    }
};

const saveChatHistory = () => {
    sessionStorage.setItem(chatStorageKey, JSON.stringify(chatHistory.slice(-12)));
};

const renderChatHistory = () => {
    if (!aiMessages) return;

    aiMessages.innerHTML = '';

    chatHistory.forEach((message) => {
        const bubble = document.createElement('div');
        bubble.className = `ai-message ${message.role === 'user' ? 'user' : 'assistant'}`;
        bubble.textContent = message.content;
        aiMessages.append(bubble);
    });

    aiMessages.scrollTop = aiMessages.scrollHeight;
};

const setChatLoading = (loading) => {
    isChatLoading = loading;

    if (aiInput) aiInput.disabled = loading;
    if (aiSend) aiSend.disabled = loading;
};

const askPortfolioAssistant = async (message) => {
    const payloadHistory = chatHistory
        .filter((item) => item.role === 'user' || item.role === 'assistant')
        .slice(0, -2)
        .slice(-8);

    const response = await fetch('/profile/chat', {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
        },
        body: JSON.stringify({
            message,
            history: payloadHistory,
        }),
    });

    if (!response.ok) {
        throw new Error('Chat request failed.');
    }

    return response.json();
};

if (aiToggle && aiPanel) {
    aiToggle.addEventListener('click', () => {
        aiPanel.classList.toggle('open');
        loadChatHistory();
        renderChatHistory();
        aiInput?.focus();
    });
}

if (aiForm && aiInput) {
    loadChatHistory();
    renderChatHistory();

    aiForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = aiInput.value.trim();
        if (!message || isChatLoading) return;

        chatHistory.push({ role: 'user', content: message });
        chatHistory.push({ role: 'assistant', content: 'Membaca konteks portfolio...' });
        aiInput.value = '';
        setChatLoading(true);
        renderChatHistory();

        try {
            const result = await askPortfolioAssistant(message);
            chatHistory[chatHistory.length - 1] = {
                role: 'assistant',
                content: result.reply || 'Maaf, assistant belum bisa menjawab saat ini.',
            };
        } catch {
            chatHistory[chatHistory.length - 1] = {
                role: 'assistant',
                content: 'Maaf, koneksi assistant sedang bermasalah. Silakan coba lagi atau buka halaman Contact untuk menghubungi studio.',
            };
        } finally {
            chatHistory = chatHistory.slice(-12);
            saveChatHistory();
            setChatLoading(false);
            renderChatHistory();
            aiInput.focus();
        }
    });
}

const filterTabs = document.querySelectorAll('[data-filter]');
const workCards = document.querySelectorAll('[data-category]');

if (filterTabs.length && workCards.length) {
    filterTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const filter = tab.dataset.filter;

            filterTabs.forEach((item) => item.classList.remove('active'));
            tab.classList.add('active');

            workCards.forEach((card) => {
                const shouldShow = filter === 'all' || card.dataset.category === filter;
                card.classList.toggle('is-hidden', !shouldShow);
            });
        });
    });
}

const galleryInput = document.querySelector('[data-gallery-input]');
const galleryPreview = document.querySelector('[data-gallery-preview]');
const galleryDropzone = galleryInput?.closest('.gallery-dropzone');
const coverInput = document.querySelector('[data-cover-input]');
const coverFileName = document.querySelector('[data-cover-file-name]');

if (coverInput && coverFileName) {
    coverInput.addEventListener('change', () => {
        coverFileName.textContent = coverInput.files[0]?.name?.toUpperCase() || coverFileName.textContent;
    });
}

if (galleryInput && galleryPreview && galleryDropzone) {
    let selectedGalleryFiles = [];

    const syncGalleryInput = () => {
        const transfer = new DataTransfer();

        selectedGalleryFiles.forEach((file) => transfer.items.add(file));
        galleryInput.files = transfer.files;
    };

    const renderGalleryPreview = () => {
        galleryPreview.innerHTML = '';

        selectedGalleryFiles.forEach((file, index) => {
            const figure = document.createElement('figure');
            const image = document.createElement('img');
            const caption = document.createElement('figcaption');
            const removeButton = document.createElement('button');

            image.src = URL.createObjectURL(file);
            image.alt = file.name;
            image.onload = () => URL.revokeObjectURL(image.src);
            caption.textContent = file.name.toUpperCase();
            removeButton.type = 'button';
            removeButton.className = 'gallery-remove-button';
            removeButton.setAttribute('aria-label', `Remove ${file.name}`);
            removeButton.textContent = '×';
            removeButton.addEventListener('click', () => {
                selectedGalleryFiles.splice(index, 1);
                syncGalleryInput();
                renderGalleryPreview();
            });

            figure.append(image, removeButton, caption);
            galleryPreview.append(figure);
        });
    };

    const addGalleryFiles = (files) => {
        selectedGalleryFiles = [...selectedGalleryFiles, ...[...files]];
        syncGalleryInput();
        renderGalleryPreview();
    };

    galleryInput.addEventListener('change', () => addGalleryFiles(galleryInput.files));

    ['dragenter', 'dragover'].forEach((eventName) => {
        galleryDropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            galleryDropzone.classList.add('is-dragover');
        });
    });

    ['dragleave', 'drop'].forEach((eventName) => {
        galleryDropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            galleryDropzone.classList.remove('is-dragover');
        });
    });

    galleryDropzone.addEventListener('drop', (event) => {
        addGalleryFiles(event.dataTransfer.files);
    });
}

const checkAll = document.querySelector('[data-check-all]');
const messageChecks = document.querySelectorAll('[data-message-check]');

if (checkAll && messageChecks.length) {
    checkAll.addEventListener('change', () => {
        messageChecks.forEach((checkbox) => {
            checkbox.checked = checkAll.checked;
        });
    });
}
