<div id="vuejs-roles">
    <roles
            :user-id="{{ $user->id }}"
            :user-roles-data='@json($user->roles)'
            :roles='@json($roles)'
            endpoint="{{ route('admin.users.tabs.roles.update', $user) }}"
    />
</div>

@section('js')
    <script src="{{ mix('vue.js', 'vendor/kieranfyi/roles') }}"></script>
@endsection