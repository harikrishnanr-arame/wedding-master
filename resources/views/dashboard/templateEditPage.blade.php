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
                        </div> 
                    
                    @elseif($field['type'] == 'toggle')
                        <div style="display:flex; align-items:center; gap:10px;">
                            {{-- IMPORTANT: Default to 'checked' if value is not set --}}
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

        <button id="saveBtn" class="save-btn">Save & Publish Site</button>
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
    let content = {!! json_encode($content ?? []) !!};

    iframe.onload = function(){
        doc = iframe.contentDocument || iframe.contentWindow.document;
        if(!doc) return;
        renderAll();
        loadRepeatersToSidebar();
    };

    let lastTimerDate = null;

    function renderAll() {
        if (!doc) return;

        // 1. Simple Text Sync
        doc.querySelectorAll("[data-edit]").forEach(el => {
            const key = el.dataset.edit;
            if (content[key] !== undefined) el.textContent = content[key];
        });

        // 2. Visibility Toggles - DEFAULT TO VISIBLE IF UNDEFINED
        doc.querySelectorAll("[data-edit-toggle]").forEach(el => {
            const key = el.dataset.editToggle;
            const isVisible = (content[key] === undefined || content[key] == 1 || content[key] === true);
            el.style.display = isVisible ? "" : "none";
        });

        // 3. Images
        doc.querySelectorAll("[data-edit-image]").forEach(el => {
            const key = el.dataset.editImage;
            if (content[key]) el.src = content[key].startsWith("data:") ? content[key] : "/storage/" + content[key];
        });

        // 4. Hero Background
        doc.querySelectorAll("[data-edit-bg]").forEach(el => {
            const key = el.dataset.editBg;
            if (content[key]) {
                const url = content[key].startsWith("data:") ? content[key] : "/storage/" + content[key];
                el.style.backgroundImage = `url('${url}')`;
            }
        });

        // 5. Timer
        if (content.countdown_target && content.countdown_target !== lastTimerDate) {
            lastTimerDate = content.countdown_target;
            if (iframe.contentWindow.startTimer) {
                iframe.contentWindow.startTimer(content.countdown_target);
            }
        }

        // 6. Primary Color
        if (content.primary_color) {
            doc.documentElement.style.setProperty('--primary', content.primary_color);
        }

        // if(content.gallery && content.gallery.length){
        //     const galleryEditor = document.querySelector('.gallery-editor');
        //     content.gallery.forEach(img => {
        //         const image = document.createElement("img");
        //         image.src = img.startsWith("data:") ? img : "/storage/" + img;
        //         image.className = "preview-img-sm";
        //         galleryEditor.appendChild(image);
        //     });
        // }

        renderRepeaters();
        renderMap();
        renderGallery();
    }

    function renderGallery() {
    if (!doc) return;

    const gallerySection = doc.getElementById("gallery");
    const galleryBox = doc.getElementById("dynamicGallery");
    if (!galleryBox) return;

    // Detect gallery field dynamically
    const galleryKey = Object.keys(content).find(key =>
        Array.isArray(content[key])
    );

    if (!galleryKey) return;

    // Toggle handling
    if (content.show_gallery == 0 || content.show_gallery === false) {
        if (gallerySection) gallerySection.style.display = "none";
        return;
    } else {
        if (gallerySection) gallerySection.style.display = "";
    }

    if (!content[galleryKey] || content[galleryKey].length === 0) return;

    galleryBox.innerHTML = "";

    content[galleryKey].forEach(img => {
        const image = doc.createElement("img"); // IMPORTANT: use iframe doc
        image.src = img.startsWith("data:")
            ? img
            : "/storage/" + img;

        galleryBox.appendChild(image);
    });
}

    function renderRepeaters() {
        const storyBox = doc.getElementById("loveStoryContainer");
        if(storyBox && content.love_story) {
            storyBox.innerHTML = content.love_story.map(item => `
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
        if(eventBox && content.events) {
            eventBox.innerHTML = content.events.map(item => `
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
        if(frame && content.map_location) {
            frame.src = `https://maps.google.com/maps?q=${encodeURIComponent(content.map_location)}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
        }
    }

    // Input Event Listeners
    document.querySelectorAll("[data-field]").forEach(input => {
        input.addEventListener("change", function() {
            const key = this.dataset.field;
            content[key] = (this.type === "checkbox") ? (this.checked ? 1 : 0) : this.value;
            renderAll();
        });
        // Added 'input' for real-time text typing
        if(input.type !== "checkbox" && input.type !== "file") {
            input.addEventListener("input", function() {
                content[this.dataset.field] = this.value;
                renderAll();
            });
        }
    });

    // Image Upload Logic
    document.querySelectorAll(".drop-zone").forEach(zone => {
        const input = zone.querySelector("input");
        const key = zone.dataset.field;
        zone.onclick = (e) => { if(e.target !== input) input.click(); };
        input.onchange = () => {
            if(!input.files.length) return;
            const reader = new FileReader();
            reader.onload = e => {
                content[key] = e.target.result;
                const container = zone.querySelector(".img-preview-container");
                if(container) container.innerHTML = `<img class="preview-img-sm" src="${e.target.result}">`;
                renderAll();
            };
            reader.readAsDataURL(input.files[0]);
        };
    });

    /* REPEATER LOGIC */
    function loadRepeatersToSidebar() {
        if(content.love_story) content.love_story.forEach((item, i) => addRepeater("love_story", item, i));
        if(content.events) content.events.forEach((item, i) => addRepeater("events", item, i));
    }

    window.addRepeater = function(name, data = null, existingIdx = null) {
        const container = document.querySelector(`[data-repeater="${name}"] .repeater-container`);
        const index = existingIdx !== null ? existingIdx : (content[name] ? content[name].length : 0);
        
        if(!content[name]) content[name] = [];
        if(data === null) {
            const newItem = {title: '', description: '', image: '', date: '', location: ''};
            content[name].push(newItem);
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

    window.updateRep = (name, idx, key, val) => { content[name][idx][key] = val; renderRepeaters(); };
    window.updateRepFile = (name, idx, key, input) => {
        const reader = new FileReader();
        reader.onload = e => { content[name][idx][key] = e.target.result; renderRepeaters(); };
        reader.readAsDataURL(input.files[0]);
    };
    window.removeRep = (name, idx, btn) => { content[name].splice(idx, 1); btn.parentElement.remove(); renderRepeaters(); };

    // SAVE AJAX
    saveBtn.addEventListener("click", async function () {

        saveBtn.innerText = "Saving...";
        saveBtn.disabled = true;

        const formData = new FormData();

        // Append gallery files
        window.galleryFiles.forEach(file => {
            formData.append("gallery_images[]", file);
        });

        // Remove base64 images from JSON before sending
        let cleanContent = JSON.parse(JSON.stringify(content));

        formData.append("content", JSON.stringify(cleanContent));

        try {
            const response = await fetch("{{ route('template.save', $userTemplate->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                alert("Saved Successfully!");
                location.reload();
            } else {
                alert(result.error || "Error occurred");
            }

        } catch (error) {
            alert("Upload failed.");
        }

        saveBtn.innerText = "Save & Publish Site";
        saveBtn.disabled = false;
    });

    window.galleryFiles = [];

    window.handleGalleryUpload = function(input, fieldName) {
        if (!content[fieldName]) content[fieldName] = [];

        const galleryEditor = document.querySelector(`.gallery-editor[data-field="${fieldName}"]`);

        Array.from(input.files).forEach(file => {
            window.galleryFiles.push(file);

            const reader = new FileReader();
            reader.onload = e => {
                content[fieldName].push(e.target.result);

                const img = document.createElement("img");
                img.src = e.target.result;
                img.className = "preview-img-sm";
                galleryEditor.appendChild(img);

                renderAll();
            };
            reader.readAsDataURL(file);
        });

        input.value = "";
    };
});
</script>
@endsection