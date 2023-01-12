<template>
    <h6 class="text-center">
        Roles
        <span class="spinner-border spinner-border-sm float-right" role="status" v-if="loading || !this.roles.length"></span>
        <template v-else-if="user.access.edit">
            <template v-if="edit">
                <i class="fas fa-save float-right text-success" style="cursor: pointer" @click="update"></i>
                <i class="far fa-trash-alt float-right text-danger mr-1" style="cursor: pointer"
                   @click="edit = false"></i>
            </template>
            <i class="fas fa-pencil-alt float-right text-primary" style="cursor: pointer" @click="toggle"
               v-else></i>
        </template>
    </h6>

    <div v-if="edit">
        <label v-for="item in roles" :key="item.id" class="mr-3">
            <input type="checkbox" v-model="rolesToSave" :value="item.id" :disabled="loading"/>
            {{ item.name }}
        </label>
    </div>

    <div v-else class="text-center">
        <span class="badge badge-primary mr-1"
              v-for="item in userRoles" v-if="userRoles.length">
        {{ item.name }}
        </span>
        <span class="text-warning" v-else>
            No roles assigned
        </span>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            loading: false,
            edit: false,
            rolesToSave: [],
            userRoles: [],
            roles: []
        }
    },
    methods: {
        toggle: function () {
            this.edit = !this.edit;
        },
        update: function () {
            let $this = this;
            $this.loading = true;
            console.log($this.rolesToSave);
            $this.$axios
                .patch(route('admin.api.users.roles.update', {user: this.user}), {roles: $this.rolesToSave})
                .then((response) => {
                    $this.userRoles = response.data;
                    $this.edit = false;
                })
                .catch((error) => {
                    if (!error.response) {
                        return;
                    }
                    if (error.response.data.message) {
                        alert(error.response.data.message);
                    } else {
                        alert('An unknown error has occurred');
                    }
                })
                .finally(() => {
                    $this.loading = false;
                })
        },
        fetchData() {
            this.loading = true;
            this.$axios
                .get(route('admin.api.users.roles.show', {user: this.user}))
                .then((response) => {
                    this.rolesToSave = this.$lodash.map(response.data, 'id')
                })
                .then(() => {
                    this.$axios
                        .get(route('admin.api.roles.index'))
                        .then((response) => {
                            this.roles = response.data;
                        });
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
    created() {
        this.update = this.$lodash.debounce(this.update, 300);
        this.fetchData = this.$lodash.debounce(this.fetchData, 300);
    },
    mounted() {
        this.fetchData();
    }
}
</script>