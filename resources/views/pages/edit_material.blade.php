@extends('template.app')
@section('content')
@section('title', $material->title)
    <div class="container">
        <div class="add-product-form">
            <h1>Изменение материала</h1>
            
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

            <form action="{{ route('material.update', $material->id) }}" method="post" id="materialForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="material-settings">
                    <div class="settings-item">
                        <label for="tag_id">Тег</label>
                        <select name="tag_id" id="tag_id" required>
                            <option value="">Выберите тег</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ $material->tag_id == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="settings-item">
                        <label for="isPrivate">Приватный?</label>
                        <input type="checkbox" name="isPrivate" id="isPrivate" value="1" {{ $material->isPrivate ? 'checked' : '' }}>
                    </div>

                    <div class="settings-item">
                        <label for="isDisabled">Запретить комментарии?</label>
                        <input type="checkbox" name="isDisabled" id="isDisabled" value="1" {{ $material->isDisabled ? 'checked' : '' }}>
                    </div>
                </div>

                <label for="title">Название материала</label>
                <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}" required>

                <div class="sections-container">
                    <h3>Секции материала</h3>
                    <div id="sections-list">
                        @foreach($material->section->sortBy('order') as $section)
                            <div class="section-item" data-type="{{ $section->type }}" data-order="{{ $section->order }}">
                                <div class="section-header">
                                    <span class="section-type">
                                        @if($section->isText()) Текст
                                        @elseif($section->isCode()) Код
                                        @elseif($section->isImage()) Изображение
                                        @endif
                                    </span>
                                    <span class="section-order">#{{ $section->order }}</span>
                                    <button type="button" class="remove-section-btn">×</button>
                                </div>
                                <div class="section-content">
                                    @if($section->isText())
                                        <textarea name="sections[{{ $section->order }}][content]" class="section-textarea" placeholder="Введите текст..." required>{{ $section->content }}</textarea>
                                        <input type="hidden" name="sections[{{ $section->order }}][id]" value="{{ $section->id }}">
                                    @elseif($section->isCode())
                                        <div>
                                            <select name="sections[{{ $section->order }}][language]" class="section-language" required>
                                                <option value="">Выберите язык</option>
                                                <option value="php" {{ $section->language == 'php' ? 'selected' : '' }}>PHP</option>
                                                <option value="javascript" {{ $section->language == 'javascript' ? 'selected' : '' }}>JavaScript</option>
                                                <option value="python" {{ $section->language == 'python' ? 'selected' : '' }}>Python</option>
                                                <option value="html" {{ $section->language == 'html' ? 'selected' : '' }}>HTML</option>
                                                <option value="css" {{ $section->language == 'css' ? 'selected' : '' }}>CSS</option>
                                                <option value="sql" {{ $section->language == 'sql' ? 'selected' : '' }}>SQL</option>
                                                <option value="java" {{ $section->language == 'java' ? 'selected' : '' }}>Java</option>
                                                <option value="csharp" {{ $section->language == 'csharp' ? 'selected' : '' }}>C#</option>
                                            </select>
                                            <textarea name="sections[{{ $section->order }}][content]" class="section-textarea section-code" placeholder="Введите код..." required>{{ $section->content }}</textarea>
                                        </div>
                                        <input type="hidden" name="sections[{{ $section->order }}][id]" value="{{ $section->id }}">
                                    @elseif($section->isImage())
                                        <div class="image-upload-area" onclick="document.getElementById('image-{{ $section->order }}').click()">
                                            <p>Нажмите для загрузки нового скриншота или перетащите файл</p>
                                            <input type="file" id="image-{{ $section->order }}" name="sections[{{ $section->order }}][image]" accept="image/*" style="display: none;" onchange="handleImageUpload(this, {{ $section->order }})">
                                            <div id="image-preview-{{ $section->order }}">
                                                @if($section->image_url)
                                                    <img src="{{ $section->image_url }}" class="image-preview" alt="Current image">
                                                    <p>Текущее изображение: {{ $section->image_name }}</p>
                                                @endif
                                            </div>
                                            <input type="text" name="sections[{{ $section->order }}][image_alt]" placeholder="Описание изображения (необязательно)" value="{{ $section->image_alt }}" style="width: 100%; margin-top: 10px; padding: 5px;">
                                            <input type="hidden" name="sections[{{ $section->order }}][id]" value="{{ $section->id }}">
                                        </div>
                                    @endif
                                </div>
                                <input type="hidden" name="sections[{{ $section->order }}][type]" value="{{ $section->type }}">
                                <input type="hidden" name="sections[{{ $section->order }}][order]" value="{{ $section->order }}">
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="section-actions">
                        <button type="button" class="add-section-btn" data-type="text">+ Текст</button>
                        <button type="button" class="add-section-btn" data-type="code">+ Код</button>
                        <button type="button" class="add-section-btn" data-type="image">+ Изображение</button>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="end-create-button">Сохранить изменения</button>
                    <button type="button" class="delete-button" id="delete-material-btn">Удалить материал</button>
                </div>
            </form>

            <form id="delete-form" action="{{ route('material.destroy', $material->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <script>
        let sectionCount = {{ $material->section->count() }};

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
                                <input type="file" id="image-${sectionCount}" name="sections[${sectionCount}][image]" accept="image/*" style="display: none;" onchange="handleImageUpload(this, ${sectionCount})">
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

            document.getElementById('delete-material-btn').addEventListener('click', function() {
                if (confirm('Вы уверены, что хотите удалить этот материал? Это действие нельзя отменить.')) {
                    document.getElementById('delete-form').submit();
                }
            });

            function confirmDelete() {
                if (confirm('Вы уверены, что хотите удалить этот материал? Это действие нельзя отменить.')) {
                    document.getElementById('delete-form').submit();
                }
            }
        });
    </script>
@endsection