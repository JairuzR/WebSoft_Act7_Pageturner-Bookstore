@extends('layouts.app')

@section('title', 'Audit Log Details - Admin')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Audit Log Details</h1>
    <p class="mt-2 text-gray-600">
        <a href="{{ route('admin.audit-logs.index') }}" class="text-brown-600 hover:text-brown-800">← Back to Audit Logs</a>
    </p>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold">
                {{ ucfirst($audit->event) }} Event - {{ $audit->created_at->format('F j, Y, g:i a') }}
            </h2>
        </div>
        
        <div class="p-6 space-y-6">
            {{-- Basic Information --}}
            <div>
                <h3 class="text-md font-semibold mb-3 text-gray-700">Event Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($audit->event == 'created') bg-green-100 text-green-800
                                @elseif($audit->event == 'updated') bg-blue-100 text-blue-800
                                @elseif($audit->event == 'deleted') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($audit->event) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $audit->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Model Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ class_basename($audit->auditable_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Model ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $audit->auditable_id }}</dd>
                    </div>
                </dl>
            </div>
            
            {{-- User Information --}}
            <div>
                <h3 class="text-md font-semibold mb-3 text-gray-700">User Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $audit->user->name ?? 'System/Console' }}
                            @if($audit->user)
                                <span class="text-gray-500">({{ $audit->user->email }})</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $audit->ip_address ?? 'N/A' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">{{ $audit->user_agent ?? 'N/A' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">URL</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $audit->url ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
            
            {{-- Changed Values --}}
            @if($audit->event == 'updated')
            <div>
                <h3 class="text-md font-semibold mb-3 text-gray-700">Changed Values</h3>
                
                @php
                    $oldValues = $audit->old_values;
                    $newValues = $audit->new_values;
                    $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
                @endphp
                
                @if(count($changedFields) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Field</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Old Value</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">New Value</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($changedFields as $field)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-700">{{ $field }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">
                                        @if(is_array($oldValues[$field] ?? null))
                                            <pre class="text-xs">{{ json_encode($oldValues[$field], JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $oldValues[$field] ?? 'null' }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        @if(is_array($newValues[$field] ?? null))
                                            <pre class="text-xs">{{ json_encode($newValues[$field], JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $newValues[$field] ?? 'null' }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No changes detected or values not captured.</p>
                @endif
            </div>
            @endif
            
            {{-- Created Values --}}
            @if($audit->event == 'created')
            <div>
                <h3 class="text-md font-semibold mb-3 text-gray-700">Created Values</h3>
                <div class="bg-gray-50 rounded p-4">
                    <pre class="text-sm text-gray-700 overflow-x-auto">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
            
            {{-- Deleted Values --}}
            @if($audit->event == 'deleted')
            <div>
                <h3 class="text-md font-semibold mb-3 text-gray-700">Deleted Record Values</h3>
                <div class="bg-gray-50 rounded p-4">
                    <pre class="text-sm text-gray-700 overflow-x-auto">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection