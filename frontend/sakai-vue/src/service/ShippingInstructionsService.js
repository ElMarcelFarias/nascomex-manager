// src/service/ShippingInstructionsService.js
export class ShippingInstructionsService {
    getShippingInstructions() {
        return fetch('http://localhost:8989/api/shipping-instruction', { headers: { 'Cache-Control': 'no-cache' } })
            .then((res) => res.json())
            .then((d) => d.shippingInstructions);
    }

    postShippingInstruction(shippingInstruction) {
        return fetch('http://localhost:8989/api/shipping-instruction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(shippingInstruction)
        })
        .then(res => res.json());
    }

    putShippingInstruction(shippingInstruction) {
        return fetch(`http://localhost:8989/api/shipping-instruction/${shippingInstruction.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(shippingInstruction)
        });
    }

    deleteShippingInstruction(id) {
        return fetch(`http://localhost:8989/api/shipping-instruction/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json());
    }

    getImports() {
        return fetch('http://localhost:8989/api/import', { headers: { 'Cache-Control': 'no-cache' } })
            .then((res) => res.json())
            .then((d) => d.imports);
    }

    getHarbors() {
        return fetch('http://localhost:8989/api/harbor', { headers: { 'Cache-Control': 'no-cache' } })
            .then((res) => res.json())
            .then((d) => d.harbors);
    }

    getBanks() {
        return fetch('http://localhost:8989/api/bank', { headers: { 'Cache-Control': 'no-cache' } })
            .then((res) => res.json())
            .then((d) => d.banks);
    }
}
