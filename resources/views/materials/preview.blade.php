<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $material->title }}</title>
    <style>
        body { margin: 0; background: #e9ecef; font-family: sans-serif; }
        #status { text-align: center; padding: 40px; color: #555; }
        #viewer { padding: 20px 0; }
    </style>
</head>
<body>
    <div id="status">Memuat dokumen...</div>
    <div id="viewer"></div>

    <script src="https://unpkg.com/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://unpkg.com/docx-preview@0.3.5/dist/docx-preview.min.js"></script>
    <script>
        fetch(@json(route('materials.download', $material)))
            .then(res => {
                if (!res.ok) throw new Error('Gagal mengambil file');
                return res.blob();
            })
            .then(blob => docx.renderAsync(blob, document.getElementById('viewer')))
            .then(() => document.getElementById('status').remove())
            .catch(() => {
                document.getElementById('status').textContent =
                    'Dokumen tidak bisa ditampilkan. Silakan gunakan tombol Unduh.';
            });
    </script>
</body>
</html>