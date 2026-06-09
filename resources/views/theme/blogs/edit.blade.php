@extends('theme.master')
@section('title', 'Edit Blog')

@section('content')
    <!--================ Hero sm banner start =================-->
    @include('theme.partials.hero', ['title' => $blog->name])
    <!--================ Hero sm banner end =================-->
    <!-- ================ contact section start ================= -->
    <section class="section-margin--small section-margin">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if (session('blog_updated'))
                        <div class="alert alert-success">
                            {{ session('blog_updated') }}
                        </div>
                    @endif
                    <form action="{{ route('blogs.update', ['blog' => $blog]) }}" class="form-contact contact_form"
                        method="post" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input class="form-control border" name="name" type="text"
                                placeholder="Enter your blog title" value="{{ $blog->name }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <input class="form-control border" name="image" type="file">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <select class="form-control border" name="category_id" type="text"
                                value="{{ old('category_id') }}">
                                <option value="">Select a category</option>
                                @if (count($categories) > 0)
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $blog->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>
                        <div class="form-group">
                            <textarea class="form-control border w-100" name="description" placeholder="Enter blog description"
                                style="height:130px !important;">{{ $blog->description }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <button type="submit" class="button button--active button-contactForm">Update Blog</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ contact section end ================= -->

@endsection
