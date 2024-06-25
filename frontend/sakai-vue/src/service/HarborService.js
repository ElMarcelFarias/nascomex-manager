

// src/service/HarborService.js
export class HarborService {
    getHarbors() {
        return fetch('http://localhost:8989/api/harbor', { headers: { 'Cache-Control': 'no-cache' } })
            .then((res) => res.json())
            .then((d) => d.harbors);
    }

    postHarbor(harbor) {
        return fetch('http://localhost:8989/api/harbor', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(harbor)
        })
        .then(res => res.json());
    }

    putHarbor(harbor) {
        return fetch(`http://localhost:8989/api/harbor/${harbor.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(harbor)
        })
    }

    deleteHarbor(id) {
        return fetch(`http://localhost:8989/api/harbor/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json());
    }

}
