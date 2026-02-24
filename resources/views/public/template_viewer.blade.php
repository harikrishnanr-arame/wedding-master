<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $content['couple_name'] ?? 'Our Wedding' }}</title>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        iframe { width: 100%; height: 100vh; border: none; display: block; }
    </style>
</head>
<body>

    <iframe id="pubFrame" src="{{ asset('storage/' . $templatePath) }}"></iframe>

    <script>
        // 1. Data Initialization
        const content = {!! json_encode($content) !!};
        const iframe = document.getElementById('pubFrame');

        iframe.onload = function() {
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            if(!doc) return;

            // 2. Text Injection
            doc.querySelectorAll("[data-edit]").forEach(el => {
                const key = el.dataset.edit;
                if (content[key] !== undefined) {
                    el.textContent = content[key];
                }
            });

            // 3. Section Toggles
            doc.querySelectorAll("[data-edit-toggle]").forEach(el => {
                const key = el.dataset.editToggle;
                // Default to visible (true) if key doesn't exist
                const isVisible = (content[key] == 1 || content[key] === true || content[key] === undefined);
                el.style.display = isVisible ? "" : "none";
            });

            // 4. Single Image Injection (Profile pics, etc.)
            doc.querySelectorAll("[data-edit-image]").forEach(el => {
                const key = el.dataset.editImage;
                if (content[key]) {
                    el.src = content[key].startsWith("data:") ? content[key] : "/storage/" + content[key];
                }
            });

            // 5. Background Image Injection (Hero sections)
            doc.querySelectorAll("[data-edit-bg]").forEach(el => {
                const key = el.dataset.editBg;
                if (content[key]) {
                    const url = content[key].startsWith("data:") ? content[key] : "/storage/" + content[key];
                    el.style.backgroundImage = `url('${url}')`;
                }
            });

            // 6. Color Theme Injection
            if (content.primary_color) {
                doc.documentElement.style.setProperty('--primary', content.primary_color);
            }

            // 7. Dynamic Gallery Grid
            const galleryBox = doc.getElementById("dynamicGallery");
            if (galleryBox && content.gallery && Array.isArray(content.gallery)) {
                galleryBox.innerHTML = "";
                content.gallery.forEach(path => {
                    const img = document.createElement("img");
                    img.src = path.startsWith("data:") ? path : "/storage/" + path;
                    galleryBox.appendChild(img);
                });
            }

            // 8. Love Story Repeater
            const storyBox = doc.getElementById("loveStoryContainer");
            if(storyBox && content.love_story) {
                storyBox.innerHTML = content.love_story.map(item => {
                    const imgSrc = item.image ? (item.image.startsWith('data:') ? item.image : '/storage/'+item.image) : '';
                    return `
                        <div class="story-item">
                            ${imgSrc ? `<img class="story-img" src="${imgSrc}">` : ''}
                            <div class="story-text">
                                <h3>${item.title || ''}</h3>
                                <p>${item.description || ''}</p>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            // 9. Events Repeater
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

            // 10. Map Injection (The specific fix for your issue)
            const mapFrame = doc.getElementById("mapFrame");
            if (mapFrame && content.map_location) {
                const encodedLocation = encodeURIComponent(content.map_location);
                // Standard Google Maps Embed URL
                mapFrame.src = `https://www.google.com/maps?q=${encodedLocation}&output=embed`;
            }

            // 11. Trigger Template Scripts (Timer)
            if (iframe.contentWindow.startTimer && content.countdown_target) {
                iframe.contentWindow.startTimer(content.countdown_target);
            }
        };
    </script>
</body>
</html>