@extends('layouts.dashboard')

@section('content')

<style>
    :root { 
        --sidebar-width: 420px; 
        --accent: #d63384;
    }
    body { background: #f4f7f6; margin: 0; }
    .editor-container { display: flex; height: 100vh; overflow: hidden; }
    
    /* Sidebar Styling */
    .editor-sidebar { 
        width: var(--sidebar-width); 
        background: #ffffff; 
        padding: 0; 
        overflow-y: auto; 
        border-right: 1px solid #e0e0e0; 
        display: flex;
        flex-direction: column;
    }
    .sidebar-header { padding: 25px; border-bottom: 1px solid #f0f0f0; background: #fff; position: sticky; top: 0; z-index: 20; }
    .sidebar-content { padding: 25px; flex: 1; }

    /* Preview Area */
    .preview-area { 
        flex: 1; 
        background: #2c2c2c; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        padding: 40px; 
        position: relative;
    }
    .preview-window {
        width: 100%;
        height: 100%;
        max-width: 1200px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        overflow: hidden;
        transition: width 0.3s ease;
    }
    iframe { width: 100%; height: 100%; border: none; }

    /* Form UI Elements */
    .form-group { margin-bottom: 25px; }
    .form-group label { display: block; font-weight: 600; font-size: 13px; text-transform: uppercase; color: #666; margin-bottom: 8px; }
    .form-control { 
        width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; 
        font-family: 'Poppins', sans-serif; font-size: 14px; transition: border 0.3s;
    }
    .form-control:focus { border-color: var(--accent); outline: none; }
    
    .repeater-item { 
        background: #f8f9fa; padding: 15px; border-radius: 10px; 
        margin-bottom: 15px; border: 1px solid #eee; position: relative;
    }
    .remove-btn { color: #ff4d4d; border: none; background: none; cursor: pointer; font-size: 12px; margin-top: 8px; font-weight: 600; }
    .add-btn { 
        width: 100%; padding: 12px; background: #fff; border: 2px dashed #ddd; 
        cursor: pointer; border-radius: 8px; font-weight: 600; color: #888; transition: 0.3s;
    }
    .add-btn:hover { border-color: var(--accent); color: var(--accent); background: #fff0f6; }
    
    .drop-zone { 
        border: 2px dashed #ddd; padding: 20px; text-align: center; border-radius: 10px; 
        cursor: pointer; position: relative; transition: 0.3s; background: #fafafa;
    }
    .drop-zone:hover { border-color: var(--accent); background: #fff0f6; }
    
    /* Gallery Sidebar Previews */
    .gallery-preview-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 15px;
    }
    .gallery-item-wrapper {
        position: relative;
        aspect-ratio: 1/1;
    }
    .gallery-item-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }
    .delete-img-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff4d4d;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .preview-img-sm { width: 100%; height: 120px; object-fit: cover; border-radius: 6px; margin-top: 10px; }

    .save-btn { 
        margin: 25px; padding: 16px; background: var(--accent); color: #fff; 
        border: none; border-radius: 8px; font-weight: bold; cursor: pointer; 
        font-size: 16px; transition: transform 0.2s, background 0.3s;
    }
    .save-btn:hover { background: #b8266d; transform: translateY(-2px); }
</style>

<div class="editor-container">
    <div class="editor-sidebar">
        <div class="sidebar-header">
            <h2 style="margin:0; font-family: 'Playfair Display', serif;">Design Studio</h2>
            <p style="font-size: 12px; color: #999; margin: 5px 0 0;">Editing: {{ $userTemplate->title ?? 'Untitled Wedding' }}</p>
        </div>

        <div class="sidebar-content">
            @foreach($fields as $field)
                <div class="form-group">
                    <label>{{ $field['label'] }}</label>

                    @if($field['type'] == 'text')
                        <input type="text" class="form-control" data-field="{{ $field['name'] }}" value="{{ $content[$field['name']] ?? '' }}">
                    
                    @elseif($field['type'] == 'textarea')
                        <textarea class="form-control" rows="3" data-field="{{ $field['name'] }}">{{ $content[$field['name']] ?? '' }}</textarea>
                    
                    @elseif($field['type'] == 'color')
                        <input type="color" class="form-control" style="height:45px; padding:5px;" data-field="{{ $field['name'] }}" value="{{ $content[$field['name']] ?? '#d63384' }}">
                    
                    @elseif($field['type'] == 'datetime')
                        <input type="datetime-local" class="form-control" data-field="{{ $field['name'] }}" value="{{ $content[$field['name']] ?? '' }}">
                    
                    @elseif($field['type'] == 'gallery')
                        <div class="gallery-editor" data-field="{{ $field['name'] }}">
                            <div class="drop-zone gallery-drop-zone" onclick="this.querySelector('input').click()">
                                <p style="font-size: 12px; margin:0;">+ Add Gallery Images</p>
                                <input type="file" hidden accept="image/*" multiple 
                                    onchange="handleGalleryUpload(this, '{{ $field['name'] }}')">
                            </div>
                            <div class="gallery-preview-container" id="sidebar-gallery-list">
                                </div>
                        </div> 
                    
                    @elseif($field['type'] == 'toggle')
                        <div style="display:flex; align-items:center; gap:10px;">
                            <input type="checkbox" 
                                   style="width:20px; height:20px;" 
                                   data-field="{{ $field['name'] }}" 
                                   {{ (!isset($content[$field['name']]) || $content[$field['name']] == 1) ? 'checked' : '' }}>
                            <span style="font-size:14px;">Show this section</span>
                        </div>

                    @elseif($field['type'] == 'image')
                        <div class="drop-zone" data-field="{{ $field['name'] }}">
                            <p style="font-size: 12px; margin:0;">Click to upload image</p>
                            <input type="file" hidden accept="image/*">
                            <div class="img-preview-container">
                                @if(!empty($content[$field['name']]))
                                    <img class="preview-img-sm" src="{{ (strpos($content[$field['name']], 'data:') === 0) ? $content[$field['name']] : asset('storage/'.$content[$field['name']]) }}">
                                @endif
                            </div>
                        </div>

                    @elseif($field['type'] == 'repeater')
                        <div data-repeater="{{ $field['name'] }}">
                            <div class="repeater-container"></div>
                            <button type="button" class="add-btn" onclick="addRepeater('{{ $field['name'] }}')">+ Add {{ $field['label'] }}</button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div style="display:flex; gap:10px; margin: 25px;">
            <button id="saveBtn" class="save-btn" style="flex:1; background:#6c757d;">Save</button>
            <button id="publishBtn" class="save-btn" style="flex:1; background:#28a745;">Publish</button>
        </div>
    </div>

    <div class="preview-area">
        <div class="preview-window" id="windowWrapper">
            <iframe id="templateFrame" src="{{ asset('storage/' . $templatePath) }}"></iframe>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const iframe = document.getElementById("templateFrame");
    let doc;
    
    // Global State
    window.content = {!! json_encode($content ?? []) !!};
    window.galleryFiles = []; 
    let lastTimerDate = null;

    iframe.onload = function(){
        doc = iframe.contentDocument || iframe.contentWindow.document;
        if(!doc) return;
        renderAll();
        loadRepeatersToSidebar();
        refreshGallerySidebar('gallery'); // Initial gallery sidebar load
    };

    function renderAll() {
        if (!doc) return;

        // 1. Text Sync
        doc.querySelectorAll("[data-edit]").forEach(el => {
            const key = el.dataset.edit;
            if (window.content[key] !== undefined) el.textContent = window.content[key];
        });

        // 2. Toggles
        doc.querySelectorAll("[data-edit-toggle]").forEach(el => {
            const key = el.dataset.editToggle;
            const isVisible = (window.content[key] === undefined || window.content[key] == 1 || window.content[key] === true);
            el.style.display = isVisible ? "" : "none";
        });

        // 3. Images
        doc.querySelectorAll("[data-edit-image]").forEach(el => {
            const key = el.dataset.editImage;
            if (window.content[key]) el.src = window.content[key].startsWith("data:") ? window.content[key] : "/storage/" + window.content[key];
        });

        // 4. Hero BG
        doc.querySelectorAll("[data-edit-bg]").forEach(el => {
            const key = el.dataset.editBg;
            if (window.content[key]) {
                const url = window.content[key].startsWith("data:") ? window.content[key] : "/storage/" + window.content[key];
                el.style.backgroundImage = `url('${url}')`;
            }
        });

        // 5. Timer
        if (window.content.countdown_target && window.content.countdown_target !== lastTimerDate) {
            lastTimerDate = window.content.countdown_target;
            if (iframe.contentWindow.startTimer) {
                iframe.contentWindow.startTimer(window.content.countdown_target);
            }
        }

        // 6. Color
        if (window.content.primary_color) {
            doc.documentElement.style.setProperty('--primary', window.content.primary_color);
        }

        renderRepeaters();
        renderMap();
        renderGalleryInIframe();
    }

    function renderGalleryInIframe() {
        if (!doc) return;
        const gallerySection = doc.getElementById("gallery");
        const galleryBox = doc.getElementById("dynamicGallery");
        if (!galleryBox) return;

        if (window.content.show_gallery == 0 || window.content.show_gallery === false) {
            if(gallerySection) gallerySection.style.display = "none";
            return;
        } else {
            if(gallerySection) gallerySection.style.display = "";
        }

        if (!Array.isArray(window.content.gallery) || window.content.gallery.length === 0) return;

        galleryBox.innerHTML = "";
        window.content.gallery.forEach(img => {
            const image = document.createElement("img");
            image.src = img.startsWith("data:") ? img : "/storage/" + img;
            galleryBox.appendChild(image);
        });
    }

    function renderRepeaters() {
        const storyBox = doc.getElementById("loveStoryContainer");
        if(storyBox && window.content.love_story) {
            storyBox.innerHTML = window.content.love_story.map(item => `
                <div class="story-item">
                    <img class="story-img" src="${item.image ? (item.image.startsWith('data:') ? item.image : '/storage/'+item.image) : ''}">
                    <div class="story-text">
                        <h3>${item.title || 'Our Milestone'}</h3>
                        <p>${item.description || ''}</p>
                    </div>
                </div>
            `).join('');
        }

        const eventBox = doc.getElementById("eventsContainer");
        if(eventBox && window.content.events) {
            eventBox.innerHTML = window.content.events.map(item => `
                <div class="event-card">
                    <span class="event-icon">💍</span>
                    <h3>${item.title || ''}</h3>
                    <p><strong>${item.date || ''}</strong></p>
                    <p>${item.location || ''}</p>
                </div>
            `).join('');
        }
    }

    function renderMap() {
        const frame = doc.getElementById("mapFrame");
        if(frame && window.content.map_location) {
            frame.src = `https://maps.google.com/maps?q=${encodeURIComponent(window.content.map_location)}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
        }
    }

    // Sidebar Input Listeners
    document.querySelectorAll("[data-field]").forEach(input => {
        input.addEventListener("change", function() {
            const key = this.dataset.field;
            window.content[key] = (this.type === "checkbox") ? (this.checked ? 1 : 0) : this.value;
            renderAll();
        });
        if(input.type !== "checkbox" && input.type !== "file") {
            input.addEventListener("input", function() {
                window.content[this.dataset.field] = this.value;
                renderAll();
            });
        }
    });

    // Single Image Upload Logic
    document.querySelectorAll(".drop-zone:not(.gallery-drop-zone)").forEach(zone => {
        const input = zone.querySelector("input");
        const key = zone.dataset.field;
        zone.onclick = (e) => { if(e.target !== input) input.click(); };
        input.onchange = () => {
            if(!input.files.length) return;
            const reader = new FileReader();
            reader.onload = e => {
                window.content[key] = e.target.result;
                const container = zone.querySelector(".img-preview-container");
                if(container) container.innerHTML = `<img class="preview-img-sm" src="${e.target.result}">`;
                renderAll();
            };
            reader.readAsDataURL(input.files[0]);
        };
    });

    // Gallery Logic (working but some rendering issue in the editing page. for safety keeping this logic too)
    // window.handleGalleryUpload = function(input, fieldName) {
    // // FIX: Initialize the array if it doesn't exist yet
    //     if (!window.content[fieldName]) {
    //         window.content[fieldName] = [];
    //     }

    //     Array.from(input.files).forEach(file => {
    //         window.galleryFiles.push(file);
    //         const reader = new FileReader();
    //         reader.onload = e => {
    //             window.content[fieldName].push(e.target.result);
    //             refreshGallerySidebar(fieldName);
    //             renderAll();
    //         };
    //         reader.readAsDataURL(file);
    //     });
    //     input.value = "";
    // };

    // window.refreshGallerySidebar = function(fieldName) {
    //     const container = document.getElementById('sidebar-gallery-list');
    //     if(!container) return;
    //     container.innerHTML = '';

    //     (window.content[fieldName] || []).forEach((img, index) => {
    //         const wrapper = document.createElement('div');
    //         wrapper.className = 'gallery-item-wrapper';
            
    //         const imgSrc = img.startsWith("data:") ? img : "/storage/" + img;
            
    //         wrapper.innerHTML = `
    //             <img src="${imgSrc}">
    //             <button type="button" class="delete-img-btn" onclick="removeGalleryImage('${fieldName}', ${index})">×</button>
    //         `;
    //         container.appendChild(wrapper);
    //     });
    // };

    // window.removeGalleryImage = function(fieldName, index) {
    //     window.content[fieldName].splice(index, 1);
    //     refreshGallerySidebar(fieldName);
    //     renderAll();
    // };

    // Gallery Logic
    window.handleGalleryUpload = function(input, fieldName) {
        // 1. Initialize the array if it doesn't exist
        if (!window.content[fieldName]) {
            window.content[fieldName] = [];
        }

        const files = Array.from(input.files);
        
        files.forEach(file => {
            // Store the raw file for the final upload
            window.galleryFiles.push(file);

            const reader = new FileReader();
            reader.onload = e => {
                // 2. Add the Base64 string to the local state immediately
                window.content[fieldName].push(e.target.result);
                
                // 3. Force update both views immediately
                refreshGallerySidebar(fieldName);
                renderAll(); 
            };
            reader.readAsDataURL(file);
        });

        // Clear input so user can add the same image again if they want
        input.value = "";
    };

    window.refreshGallerySidebar = function(fieldName) {
        const container = document.getElementById('sidebar-gallery-list');
        if (!container) {
            console.error("Gallery sidebar container not found!");
            return;
        }
        
        container.innerHTML = '';

        const images = window.content[fieldName] || [];

        images.forEach((img, index) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'gallery-item-wrapper';
            
            // Logic: If it starts with 'data:' it's a new upload. 
            // If it starts with 'http' it's a full URL.
            // Otherwise, it's a path from the DB that needs '/storage/'
            let imgSrc = img;
            if (!img.startsWith('data:') && !img.startsWith('http')) {
                imgSrc = "/storage/" + img;
            }
            
            wrapper.innerHTML = `
                <img src="${imgSrc}" style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
                <button type="button" class="delete-img-btn" onclick="removeGalleryImage('${fieldName}', ${index})">×</button>
            `;
            container.appendChild(wrapper);
        });
    };

    window.removeGalleryImage = function(fieldName, index) {
        window.content[fieldName].splice(index, 1);
        refreshGallerySidebar(fieldName);
        renderAll();
    };

    // Repeater Logic
    function loadRepeatersToSidebar() {
        const containers = document.querySelectorAll('.repeater-container');
        containers.forEach(c => c.innerHTML = '');
        if(window.content.love_story) window.content.love_story.forEach((item, i) => addRepeater("love_story", item, i));
        if(window.content.events) window.content.events.forEach((item, i) => addRepeater("events", item, i));
    }

    window.addRepeater = function(name, data = null, existingIdx = null) {
        const container = document.querySelector(`[data-repeater="${name}"] .repeater-container`);
        const index = existingIdx !== null ? existingIdx : (window.content[name] ? window.content[name].length : 0);
        
        if(!window.content[name]) window.content[name] = [];
        if(data === null) {
            const newItem = {title: '', description: '', image: '', date: '', location: ''};
            window.content[name].push(newItem);
            data = newItem;
        }

        let html = '';
        if(name === 'love_story') {
            html = `
            <div class="repeater-item" data-index="${index}">
                <input type="text" placeholder="Milestone Title" class="form-control" value="${data?.title || ''}" oninput="updateRep('${name}',${index},'title',this.value)">
                <textarea style="margin-top:5px" placeholder="Details..." class="form-control" oninput="updateRep('${name}',${index},'description',this.value)">${data?.description || ''}</textarea>
                <input type="file" style="margin-top:5px; font-size:11px;" onchange="updateRepFile('${name}',${index},'image',this)">
                <button class="remove-btn" onclick="removeRep('${name}', ${index}, this)">Remove Milestone</button>
            </div>`;
        } else if(name === 'events') {
            html = `
            <div class="repeater-item" data-index="${index}">
                <input type="text" placeholder="Event Name" class="form-control" value="${data?.title || ''}" oninput="updateRep('${name}',${index},'title',this.value)">
                <input type="text" style="margin-top:5px" placeholder="Time/Date" class="form-control" value="${data?.date || ''}" oninput="updateRep('${name}',${index},'date',this.value)">
                <input type="text" style="margin-top:5px" placeholder="Location" class="form-control" value="${data?.location || ''}" oninput="updateRep('${name}',${index},'location',this.value)">
                <button class="remove-btn" onclick="removeRep('${name}', ${index}, this)">Remove Event</button>
            </div>`;
        }
        container.insertAdjacentHTML('beforeend', html);
    };

    window.updateRep = (name, idx, key, val) => { window.content[name][idx][key] = val; renderRepeaters(); };
    window.updateRepFile = (name, idx, key, input) => {
        const reader = new FileReader();
        reader.onload = e => { window.content[name][idx][key] = e.target.result; renderRepeaters(); };
        reader.readAsDataURL(input.files[0]);
    };
    window.removeRep = (name, idx, btn) => { window.content[name].splice(idx, 1); btn.parentElement.remove(); renderRepeaters(); };

    //Save/Publish Logic
    const saveBtn = document.getElementById('saveBtn');
    const publishBtn = document.getElementById('publishBtn');

    //save
    async function saveTemplate(publish = false) {
        saveBtn.disabled = true;
        publishBtn.disabled = true;
        const originalText = saveBtn.innerText;
        
        // 1. Show a "Processing" toast or loading state
        saveBtn.innerText = publish ? "Publishing..." : "Saving...";

        const formData = new FormData();

        if (window.galleryFiles) {
            window.galleryFiles.forEach(file => formData.append('gallery_images[]', file));
        }

        let contentToSend = JSON.parse(JSON.stringify(window.content));
        if (contentToSend.gallery) {
            contentToSend.gallery = contentToSend.gallery.filter(img => !img.startsWith("data:"));
        }

        formData.append('content', JSON.stringify(contentToSend));
        formData.append('publish', publish ? 1 : 0);

        try {
            const response = await fetch("{{ route('template.save', $userTemplate->id) }}", {
                method: "POST",
                headers: { 
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.content = data.content; 
                window.galleryFiles = []; 
                
                if (publish && data.publish_url) {
                    // Success Alert for Publishing
                    Swal.fire({
                        title: 'Published!',
                        text: 'Your wedding website is now live.',
                        icon: 'success',
                        confirmButtonColor: '#d63384',
                        confirmButtonText: 'View Website'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(data.publish_url, '_blank');
                        }
                    });
                } else {
                    // Success Toast for Saving
                    Swal.fire({
                        title: 'Saved!',
                        text: 'Your progress has been secured.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
                
                renderAll(); 
                refreshGallerySidebar('gallery');
            } else {
                // Error Alert
                Swal.fire({
                    title: 'Error',
                    text: data.error || "Something went wrong while saving.",
                    icon: 'error',
                    confirmButtonColor: '#d63384'
                });
            }
        } catch (err) {
            // Connection Error Alert
            Swal.fire({
                title: 'Connection Failed',
                text: 'Could not reach the server. Please check your internet.',
                icon: 'warning',
                confirmButtonColor: '#d63384'
            });
        } finally {
            saveBtn.disabled = false;
            publishBtn.disabled = false;
            saveBtn.innerText = "Save";
            publishBtn.innerText = "Publish";
        }
    }

    saveBtn.addEventListener('click', () => saveTemplate(false));
    publishBtn.addEventListener('click', () => saveTemplate(true));
});
</script>
@endsection