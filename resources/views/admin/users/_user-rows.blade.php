@forelse ($users as $user)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            {{-- Updated link to point to the new route --}}
            <a href="{{ route('admin.users.show', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">View Progress</a>
        </td>
    </tr>
@empty
    {{-- This part is only sent if a page is loaded with no results --}}
    @if(request()->get('page', 1) == 1)
        <tr>
            <td colspan="4" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                <p class="font-semibold">No users found.</p>
                <p class="text-xs text-gray-400">Try adjusting your search or filter criteria.</p>
            </td>
        </tr>
    @endif
@endforelse

