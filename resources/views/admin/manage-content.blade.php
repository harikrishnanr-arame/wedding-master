@extends('admin.layout')

@section('title', 'Manage Templates')
@section('page-title', 'Manage Website Templates')

@section('content')

@if(session('success'))
<div style="background:#d4edda;color:#155724;padding:10px;margin-bottom:15px;border-radius:5px;">
    {{ session('success') }}
</div>
@endif

<!-- Add Template Button -->
<div class="header-row">
    <button class="add-btn" id="addTemplateBtn">
        + Add Template
    </button>
</div>

<!-- Templates Grid -->
<div class="templates-grid">
    @foreach($templates as $template)
<div class="panel template-card">

    <img src="{{ asset('storage/'.$template->cover_image) }}" class="template-cover">

    <p class="template-name">{{ $template->name }}</p>
    <p style="font-size:12px;color:gray;">{{ $template->category }}</p>

    {{-- Status Badge --}}
    <p class="status-badge {{ $template->is_active ? 'active' : 'inactive' }}">
        {{ $template->is_active ? 'Active' : 'Inactive' }}
    </p>

    {{-- Toggle Button --}}
    <form action="{{ route('admin.templates.toggle',$template->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button class="toggle-btn">
            {{ $template->is_active ? 'Deactivate' : 'Activate' }}
        </button>
    </form>

    {{-- Delete Button --}}
    <form action="{{ route('admin.templates.delete',$template->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="delete-btn">Delete</button>
    </form>

</div>
    @endforeach
</div>

<!-- Modal -->
<div id="templateModal" class="modal">
    <div class="modal-content">
        <h3>Add New Template</h3>

        <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($errors->any())
            <div style="background:#f8d7da;color:#721c24;padding:10px;margin-bottom:10px;border-radius:5px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="form-group">
                <label for="name">Template Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="wedding">Wedding</option>
                    <option value="haldi">Haldi</option>
                    <option value="engagement">Engagement</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cover_image">Cover Image</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="html_file">HTML File</label>
                <input type="file" id="html_file" name="html_file" accept=".html" required>
            </div>

            <div class="form-group">
                <label for="css_file">CSS File</label>
                <input type="file" id="css_file" name="css_file" accept=".css" required>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="save-btn">Add Template</button>
                <button type="button" class="save-btn cancel-btn" id="closeModal">Cancel</button>
            </div>

        </form>
    </div>
</div>

@endsection


@section('scripts')

<script>
document.getElementById('addTemplateBtn').onclick = function() {
    document.getElementById('templateModal').style.display = 'flex';
};

document.getElementById('closeModal').onclick = function() {
    document.getElementById('templateModal').style.display = 'none';
};
</script>

@endsection
