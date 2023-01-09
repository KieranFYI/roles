<template>
    <h6 class="text-center">
        Roles
        <span class="spinner-border spinner-border-sm float-right" role="status" v-if="loading"></span>
        <template v-else-if="edit">
            <i class="fas fa-save float-right text-success" style="cursor: pointer" @click="update"></i>
            <i class="fas fa-times float-right text-danger mr-1" style="cursor: pointer"
               @click="edit = false"></i>
        </template>
        <i class="fas fa-pencil-alt float-right text-primary" style="cursor: pointer" @click="toggle"
           v-else></i>
    </h6>

    <div v-if="edit">
        <label v-for="item in roles" :key="item.id" class="mr-3">
            <input type="checkbox" v-model="rolesToSave" :value="item.id"/>
            {{ item.name }}
        </label>
    </div>

    <div v-else class="text-center">
        <span class="badge badge-primary mr-1"
              v-for="item in userRoles">
        {{ item.name }}
        </span>
    </div>
</template>

<script>
export default {
    props: {
        userId: {
            required: true,
            type: Number
        },
        userRolesData: {
            required: true,
            type: Object
        },
        roles: {
            required: true,
            type: Object
        },
        endpoint: {
            required: true,
            type: String
        },
    },
    data() {
        return {
            userRoles: [],
            loading: false,
            edit: false,
            rolesToSave: [],
        }
    },
    methods: {
        toggle: function () {
            if (!this.edit) {
                this.rolesToSave = this.$lodash.map(this.userRoles, 'id');
                this.edit = true;
            } else {
                this.edit = false;
            }
        },
        update: function () {
            let $this = this;
            $this.loading = true;

            $this.$axios
                .patch($this.endpoint, {roles: $this.rolesToSave})
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
        }
    },
    created() {
        this.update = this.$lodash.debounce(this.update, 300);
    },
    beforeMount() {
        this.userRoles = this.userRolesData;
    }
}
</script>