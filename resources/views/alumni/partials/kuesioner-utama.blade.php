{{-- Bagian ini berisi combobox kustom untuk memilih status pekerjaan --}}
<div class="form-group mb-4">
    <label for="status_pekerjaan_button" class="form-label font-weight-bold">
        <h4>Jelaskan status Anda saat ini?</h4><span class="text-danger">*</span>
    </label>
    {{-- Input tersembunyi yang akan menyimpan nilai f8 --}}
    <input type="hidden" name="f8" x-model="statusTerpilih">
    
    <div class="dropdown combobox-wrapper" @click.away="open = false">
        <button @click="open = !open" type="button" id="status_pekerjaan_button" class="form-control form-control-lg combobox-button">
            <span class="d-flex align-items-center">
                <img :src="selected.image" alt="" class="avatar rounded-circle me-2" x-show="selected.value">
                <span class="truncate" x-text="selected.text"></span>
            </span>
            <i class="fas fa-chevron-down text-secondary"></i>
        </button>
        <ul class="dropdown-menu" :class="{ 'show': open }">
            <template x-for="option in options" :key="option.value">
                <li @click="selectOption(option)">
                    <a class="dropdown-item d-flex align-items-center justify-content-between">
                        <span class="d-flex align-items-center">
                            <img :src="option.image" alt="" class="avatar rounded-circle me-2">
                            <span x-text="option.text"></span>
                        </span>
                        <i class="fas fa-check text-primary dropdown-item-check" x-show="option.value === selected.value"></i>
                    </a>
                </li>
            </template>
        </ul>
    </div>
</div>
