<template>
    <div>
        <h1>
            Clients
            <a href="/clients/create" class="float-right btn btn-primary">+ New Client</a>
        </h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Number of Bookings</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="client in clients" :key="client.id">
                    <td>{{ client.name }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.phone }}</td>
                    <td>{{ client.bookings_count }}</td>
                    <td>
                        <a class="btn btn-primary btn-sm" :href="`/clients/${client.id}`">View</a>
                        <button class="btn btn-danger btn-sm" @click="deleteClient(client)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import 'vue-toastification/dist/index.css';
import Toast from 'vue-toastification';
import axios from 'axios';

Vue.use(Toast, {});

export default {
    name: 'ClientsList',

    props: ['clients'],

    methods: {
        deleteClient(client) {
            axios.delete(`/clients/${client.id}`)
                .then(() => {
                    const index = this.clients.findIndex((c) => c.id === client.id);
                    if (index !== -1) {
                        this.clients.splice(index, 1);
                        this.$forceUpdate();
                    }
                    this.$toast('Client deleted!');
                })
                .catch(() => {
                    this.$toast.error('Client was not deleted!');
                });
        }
    }
}
</script>
