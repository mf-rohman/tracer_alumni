<div class="border rounded-3 p-3 mx-3 mb-3 mt-0 bg-light">
    <h5 class="font-weight-bolder mb-2 d-flex align-items-center">
        <i class="fas fa-comments me-2 text-primary"></i>
        Testimoni
    </h5>

    <label for="testimoni_alumni" class="form-label fw-semibold">
        Silakan berikan <b>testimoni Anda</b> selama berkuliah di <b>Unirow</b> atau motivasi untuk mahasiswa lain.
    </label>

    <textarea
        name="testimoni_alumni"
        id="testimoni_alumni"
        rows="4"
        class="form-control"
        placeholder="Tulis pengalaman atau motivasi Anda di sini..."
    >{{ old('testimoni_alumni', $answer->testimoni_alumni ?? '') }}</textarea>

    <div class="form-group mb-4">
        <label for="url_linked" class="form_label fw-semibold">Linkedin</label>
        <input type="url"
            name="url_linkedin"
            id="url_linkedin"
            class="form-control"
            placeholder="https://www.linkedin.com/in/[your_username]"
            value="{{ old('url_linkedin', $answer->url_linkedin ?? '') }}"
        >
    </div>


</div>
