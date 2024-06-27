// src/service/BankService.js
export class BankService {
    getBanks() {
        return fetch('http://localhost:8989/api/bank', { headers: { 'Cache-Control': 'no-cache' } })
            .then((res) => res.json())
            .then((d) => d.banks);
    }

    postBank(bank) {
        return fetch('http://localhost:8989/api/bank', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bank)
        })
        .then(res => res.json());
    }

    putBank(bank) {
        return fetch(`http://localhost:8989/api/bank/${bank.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bank)
        });
    }

    deleteBank(id) {
        return fetch(`http://localhost:8989/api/bank/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json());
    }
}
