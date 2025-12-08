<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            User Management
        </h2>
    </x-slot>

    <div class="p-6 space-y-6" 
         x-data="{ openEdit:false, openDelete:false, selectedUser:{} }">

        <!-- USERS TABLE -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="font-semibold mb-3">Registered Users</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->role) }}</td>

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <!-- Edit Button -->
                                    <button 
                                        type="button"
                                        class="text-blue-600 hover:text-blue-900 mr-4"
                                        @click="openEdit = true; selectedUser = {{ $user }}">
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <button 
                                        type="button"
                                        class="text-red-600 hover:text-red-900"
                                        @click="openDelete = true; selectedUser = {{ $user }}">
                                        Delete
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        {{ $users->links() }}



        <!-- ========================================= -->
        <!-- EDIT USER MODAL -->
        <!-- ========================================= -->
        <div 
            x-show="openEdit"
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
        >
            <div class="bg-white p-6 rounded-xl w-96 shadow-xl">

                <h2 class="text-lg font-semibold mb-4">Edit User</h2>

                <form method="POST" :action="'/admin/users/' + selectedUser.id">
                    @csrf
                    @method('PUT')

                    <label class="block mb-2 text-sm font-medium">Name</label>
                    <input type="text" name="name" 
                        class="w-full border rounded p-2 mb-4"
                        :value="selectedUser.name">

                    <label class="block mb-2 text-sm font-medium">Email</label>
                    <input type="email" name="email" 
                        class="w-full border rounded p-2 mb-4"
                        :value="selectedUser.email">

                    <label class="block mb-2 text-sm font-medium">Phone Number</label>
                    <input type="text" name="phone"
                        class="w-full border rounded p-2 mb-4"
                        :value="selectedUser.phone">

                    <label class="block mb-2 text-sm font-medium">Address</label>
                    <input type="text" name="address"
                        class="w-full border rounded p-2 mb-4"
                        :value="selectedUser.address">

                    <label class="block mb-2 text-sm font-medium">Role</label>
                    <select name="role" class="w-full border rounded p-2 mb-4">
                        <option value="user" :selected="selectedUser.role === 'user'">User</option>
                        <option value="admin" :selected="selectedUser.role === 'admin'">Admin</option>
                    </select>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                                @click="openEdit = false"
                                class="px-4 py-2 bg-gray-200 rounded">
                            Cancel
                        </button>

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded">
                            Save
                        </button>
                    </div>
                </form>

            </div>
        </div>



        <!-- ========================================= -->
        <!-- DELETE USER MODAL -->
        <!-- ========================================= -->
        <div 
            x-show="openDelete"
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
        >
            <div class="bg-white p-6 rounded-xl w-80 text-center shadow-xl">

                <h2 class="text-lg font-semibold mb-2">Delete User?</h2>
                <p class="text-gray-600 mb-4">This action cannot be undone.</p>

                <form method="POST" :action="'/admin/users/' + selectedUser.id">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-center gap-4">

                        <button type="button"
                            @click="openDelete = false"
                            class="px-4 py-2 bg-gray-200 rounded">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded">
                            Delete
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

</x-app-layout>
