<template>
    <h6 class="text-center">
        Roles
        <span v-if="loading || !this.roles.length" class="spinner-border spinner-border-sm float-right"
              role="status"></span>
        <template v-else-if="user.access.edit">
            <template v-if="edit">
                <i class="fas fa-save float-right text-success" style="cursor: pointer" @click="update"></i>
                <i class="fas fa-times float-right text-danger mr-1" style="cursor: pointer"
                   @click="edit = false"></i>
            </template>
            <i v-else class="fas fa-pencil-alt float-right text-primary" style="cursor: pointer"
               @click="toggle"></i>
        </template>
    </h6>

    <div v-if="edit">
        <label v-for="item in roles" :key="item.id" class="mr-3">
            <input v-model="rolesToSave" :disabled="loading" :value="item.id" type="checkbox"/>
            {{ item.name }}
        </label>
    </div>

    <div v-else class="text-center">
        <div v-if="loading" class="d-flex justify-content-center">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <template v-else-if="userRoles.length !== 0">
            <span v-for="item in userRoles"
                  class="badge badge-primary mr-1">
            {{ item.name }}
            </span>
        </template>
        <span v-else class="text-warning">
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
            loading: true,
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
                    this.userRoles = response.data;
                    this.rolesToSave = this.$lodash.map(this.userRoles, 'id');

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