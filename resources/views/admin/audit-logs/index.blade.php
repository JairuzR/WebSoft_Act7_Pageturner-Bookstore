@extends('layouts.app')

@section('title', 'Audit Logs - Admin')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Audit Logs</h1>
    <p class="mt-2 text-gray-600">Track all changes made to system data</p>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model Type</label>
                <select name="auditable_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $type => $label)
                        <option value="{{ $type }}" {{ request('auditable_type') == $type ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                <select name="event" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                            {{ ucfirst($event) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
            </div>
            
            <div class="md:col-span-4 flex justify-end">
                <a href="{{ route('admin.audit-logs.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition mr-2">
                    Clear Filters
                </a>
                <button type="submit" class="bg-brown-600 text-white px-6 py-2 rounded-md hover:bg-brown-700 transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    {{-- Audit Logs Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($audits as $audit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            {{ $audit->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $audit->user->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($audit->event == 'created') bg-green-100 text-green-800
                                @elseif($audit->event == 'updated') bg-blue-100 text-blue-800
                                @elseif($audit->event == 'deleted') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($audit->event) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $audit->ip_address ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.audit-logs.show', $audit) }}" 
                               class="text-brown-600 hover:text-brown-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No audit logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $audits->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection