// src/service/ImportService.js
export class ImportService {
    getImports() {
        return fetch('http://localhost:8989/api/import', { headers: { 'Cache-Control': 'no-cache' } })
            .then((res) => res.json())
            .then((d) => d.imports);
    }

    postImport(importer) {
        return fetch('http://localhost:8989/api/import', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(importer)
        })
        .then(res => res.json());
    }

    putImport(importer) {
        return fetch(`http://localhost:8989/api/import/${importer.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(importer)
        });
    }

    deleteImport(id) {
        return fetch(`http://localhost:8989/api/import/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json());
    }
}
