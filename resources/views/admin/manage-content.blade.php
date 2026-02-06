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
                <label>Template Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="wedding">Wedding</option>
                    <option value="haldi">Haldi</option>
                    <option value="engagement">Engagement</option>
                </select>
            </div>

            <div class="form-group">
                <label>Cover Image</label>
                <input type="file" name="cover_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label>HTML File</label>
                <input type="file" name="html_file" accept=".html" required>
            </div>

            <div class="form-group">
                <label>CSS File</label>
                <input type="file" name="css_file" accept=".css" required>
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

<style>
.modal {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
}

.modal-content {
    background:#fff;
    padding:25px;
    width:400px;
    border-radius:8px;
}

.template-card {
    width:220px;
    padding:10px;
    text-align:center;
}

.template-cover {
    width:100%;
    height:140px;
    object-fit:cover;
}

.templates-grid {
    display:flex;
    flex-wrap:wrap;
    gap:20px;
}

.status-badge {
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 12px;
    display: inline-block;
    margin-bottom: 8px;
    font-weight: bold;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

.toggle-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 6px 10px;
    margin-bottom: 6px;
    cursor: pointer;
    border-radius: 4px;
}

.toggle-btn:hover {
    opacity: 0.9;
}

</style>

<script>
document.getElementById('addTemplateBtn').onclick = function() {
    document.getElementById('templateModal').style.display = 'flex';
};

document.getElementById('closeModal').onclick = function() {
    document.getElementById('templateModal').style.display = 'none';
};
</script>

@endsection
