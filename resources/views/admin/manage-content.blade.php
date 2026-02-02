@extends('admin.layout')

@section('title', 'Manage Content')
@section('page-title', 'Manage Website Templates')

@section('content')

<!-- Add Template Button -->
<div class="header-row">
    <button class="add-btn" id="addTemplateBtn">+ Add Template</button>
</div>

<!-- Templates Grid -->
<div class="templates-grid">
    {{-- Templates will be loaded here --}}
</div>

<!-- Add Template Modal -->
<div id="templateModal">
    <div class="modal-content">
        <h3>Add New Template</h3>

        <form id="addTemplateForm" enctype="multipart/form-data">
            <div class="form-group">
                <label>Template Name</label>
                <input type="text" name="name" required>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function(){

    // Sample templates
    let templates = [
        {id:1, name:"Portfolio", cover:"https://via.placeholder.com/220x140?text=Portfolio"},
        {id:2, name:"Landing Page", cover:"https://via.placeholder.com/220x140?text=Landing"},
        {id:3, name:"Ecommerce", cover:"https://via.placeholder.com/220x140?text=Ecommerce"},
    ];

    function loadTemplates(){
        let html = '';
        templates.forEach(t => {
            html += `
                <div class="panel template-card">
                    <img src="${t.cover}" class="template-cover">
                    <p class="template-name">${t.name}</p>
                    <button class="delete-btn" data-id="${t.id}">Delete</button>
                </div>
            `;
        });
        $(".templates-grid").html(html);
    }

    loadTemplates();

    // Open Modal
    $("#addTemplateBtn").click(function(){
        $("#templateModal").css("display","flex");
    });

    // Close Modal
    $("#closeModal").click(function(){
        $("#templateModal").hide();
        $("#addTemplateForm")[0].reset();
    });

    // Delete Template
    $(document).on("click",".delete-btn",function(e){
        e.stopPropagation();
        let id = $(this).data("id");
        if(confirm("Are you sure you want to delete this template?")){
            templates = templates.filter(t => t.id !== id);
            loadTemplates();
        }
    });

    // Submit Template Form
    $("#addTemplateForm").submit(function(e){
        e.preventDefault();

        let formData = new FormData(this);

        let newId = Date.now();
        templates.push({
            id: newId,
            name: formData.get("name"),
            cover: URL.createObjectURL(formData.get("cover_image"))
        });

        loadTemplates();
        $("#templateModal").hide();
        $("#addTemplateForm")[0].reset();

        alert("Template added successfully!");
    });

});
</script>
@endsection
