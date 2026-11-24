<div class="d-flex justify-content-center gap-1">
    <a href="{{ route('admin.produk.edit', $produk) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST" data-confirm="Yakin ingin menghapus/menonaktifkan produk ini?" data-confirm-button="Ya, Hapus!">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger" title="Hapus" type="submit">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
