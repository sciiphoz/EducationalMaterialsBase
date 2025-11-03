@extends('template.app')
@section('content')
@section('title', 'Добавление')
    <div class="container">
        <div class="add-product-form">
            <h1>Создание материала</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('material.store') }}" method="post" id="materialForm" enctype="multipart/form-data">
                @csrf
                
                <div class="material-settings">
                    <div class="settings-item">
                        <label for="tag_id">Тег</label>
                        <select name="tag_id" id="tag_id" required>
                            <option value="">Выберите тег</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ old('tag_id') == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="settings-item">
                        <label for="is_private">Приватный?</label>
                        <input type="checkbox" name="is_private" id="is_private" value="1" {{ old('is_private') ? 'checked' : '' }}>
                    </div>

                    <div class="settings-item">
                        <label for="disable_comments">Запретить комментарии?</label>
                        <input type="checkbox" name="disable_comments" id="disable_comments" value="1" {{ old('disable_comments') ? 'checked' : '' }}>
                    </div>
                </div>

                <label for="title">Название материала</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>

                <div class="sections-container">
                    <h3>Секции материала</h3>
                    <div id="sections-list">
                    </div>
                    
                    <div class="section-actions">
                        <button type="button" class="add-section-btn" data-type="text">+ Текст</button>
                        <button type="button" class="add-section-btn" data-type="code">+ Код</button>
                        <button type="button" class="add-section-btn" data-type="image">+ Изображение</button>
                    </div>
                </div>

                <button type="submit" class="end-create-button">Создать материал</button>
            </form>
        </div>
    </div>

    <template id="section-template">
        <div class="section-item" data-type="{type}" data-order="{order}">
            <div class="section-header">
                <span class="section-type">{typeTitle}</span>
                <span class="section-order">#{order}</span>
                <button type="button" class="remove-section-btn">×</button>
            </div>
            <div class="section-content">
                {content}
            </div>
            <input type="hidden" name="sections[{order}][type]" value="{type}">
            <input type="hidden" name="sections[{order}][order]" value="{order}">
        </div>
    </template>

    <script>
        let sectionCount = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const sectionsList = document.getElementById('sections-list');
            const sectionTemplate = document.getElementById('section-template');
            
            document.querySelectorAll('.add-section-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    addSection(this.dataset.type);
                });
            });

            function addSection(type) {
                sectionCount++;
                
                const typeTitles = {
                    'text': 'Текст',
                    'code': 'Код', 
                    'image': 'Изображение'
                };

                let content = '';
                switch(type) {
                    case 'text':
                        content = `<textarea name="sections[${sectionCount}][content]" class="section-textarea" placeholder="Введите текст..." required></textarea>`;
                        break;
                    case 'code':
                        content = `
                            <div>
                                <select name="sections[${sectionCount}][language]" class="section-language" required>
                                    <option value="">Выберите язык</option>
                                    <option value="php">PHP</option>
                                    <option value="javascript">JavaScript</option>
                                    <option value="python">Python</option>
                                    <option value="html">HTML</option>
                                    <option value="css">CSS</option>
                                    <option value="sql">SQL</option>
                                    <option value="java">Java</option>
                                    <option value="csharp">C#</option>
                                </select>
                                <textarea name="sections[${sectionCount}][content]" class="section-textarea section-code" placeholder="Введите код..." required></textarea>
                            </div>
                        `;
                        break;
                    case 'image':
                        content = `
                            <div class="image-upload-area" onclick="document.getElementById('image-${sectionCount}').click()">
                                <p>Нажмите для загрузки скриншота или перетащите файл</p>
                                <input type="file" id="image-${sectionCount}" name="sections[${sectionCount}][image]" accept="image/*" style="display: none;" onchange="handleImageUpload(this, ${sectionCount})" required>
                                <div id="image-preview-${sectionCount}"></div>
                                <input type="text" name="sections[${sectionCount}][image_alt]" placeholder="Описание изображения (необязательно)" style="width: 100%; margin-top: 10px; padding: 5px;">
                            </div>
                        `;
                        break;
                }

                const sectionHtml = sectionTemplate.innerHTML
                    .replace(/{type}/g, type)
                    .replace(/{typeTitle}/g, typeTitles[type])
                    .replace(/{order}/g, sectionCount)
                    .replace(/{content}/g, content);

                const sectionElement = document.createElement('div');
                sectionElement.innerHTML = sectionHtml;
                sectionsList.appendChild(sectionElement.firstElementChild);

                if (type === 'image') {
                    setupImageDrop(sectionCount);
                }
            }

            sectionsList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-section-btn')) {
                    e.target.closest('.section-item').remove();
                    updateSectionOrders();
                }
            });

            function updateSectionOrders() {
                const sections = sectionsList.querySelectorAll('.section-item');
                sections.forEach((section, index) => {
                    const order = index + 1;
                    section.dataset.order = order;
                    section.querySelector('.section-order').textContent = `#${order}`;
                    section.querySelector('input[name$="[order]"]').value = order;
                });
                sectionCount = sections.length;
            }

            window.handleImageUpload = function(input, order) {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById(`image-preview-${order}`);
                        preview.innerHTML = `
                            <img src="${e.target.result}" class="image-preview" alt="Preview">
                            <p>Файл: ${file.name}</p>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            }

            function setupImageDrop(order) {
                const dropArea = document.querySelector(`#image-preview-${order}`)?.closest('.image-upload-area');
                if (!dropArea) return;

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    dropArea.classList.add('dragover');
                }

                function unhighlight() {
                    dropArea.classList.remove('dragover');
                }

                dropArea.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    const input = document.getElementById(`image-${order}`);
                    
                    if (files.length > 0) {
                        input.files = files;
                        handleImageUpload(input, order);
                    }
                }
            }

            addSection('text');
        });
    </script>
@endsection