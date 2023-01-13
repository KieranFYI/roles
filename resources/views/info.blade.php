<div id="vuejs-roles">
    <roles :user='@json($user)' />
</div>

@pushOnce('js')
    @routes('admins-roles')
    @routes('admins-roles-api')
    <script src="{{ mix('vue.js', 'vendor/kieranfyi/roles') }}"></script>
@endpushOnce