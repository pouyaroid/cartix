@extends('layouts.admin')

@section('title', 'مدیریت ویجت‌ها')

@section('content')
<h5 class="fw-bold mb-4">مدیریت ویجت‌های لندینگ پیج</h5>

@foreach($widgets as $category => $items)
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-transparent">
            <h6 class="fw-bold mb-0">{{ ucfirst($category) }}</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>نام</th>
                        <th>کامپوننت</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $widget)
                        <tr>
                            <td>
                                <i class="bi {{ $widget->icon }} ms-1"></i>
                                {{ $widget->name }}
                            </td>
                            <td><code>{{ $widget->component }}</code></td>
                            <td>
                                <span class="badge bg-{{ $widget->is_active ? 'success' : 'secondary' }}">
                                    {{ $widget->is_active ? 'فعال' : 'غیرفعال' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.landing-page-widgets.toggle', $widget) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $widget->is_active ? 'warning' : 'success' }}">
                                        {{ $widget->is_active ? 'غیرفعال' : 'فعال' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
@endsection
