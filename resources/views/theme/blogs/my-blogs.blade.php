@extends('theme.master')
@section('title', 'My Blogs')

@section('content')
    <!--================ Hero sm banner start =================-->
    @include('theme.partials.hero', ['title' => 'My Blogs'])
    <!--================ Hero sm banner end =================-->
    <!-- ================ contact section start ================= -->
    <section class="section-margin--small section-margin">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if (session('blog_deleted'))
                        <div class="alert alert-success">
                            {{ session('blog_deleted') }}
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col" width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($blogs) > 0)
                                @foreach ($blogs as $blog)
                                    <tr>
                                        <td>
                                            <a href="{{ route('blogs.show', ['blog' => $blog]) }}"
                                                target="_blank">{{ $blog->name }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('blogs.edit', ['blog' => $blog]) }}"
                                                class="btn btn-sm btn-primary mr-2">Edit</a>
                                            <form action="{{ route('blogs.destroy', ['blog' => $blog]) }}" method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger mr-2"
                                                    onclick="return confirm('Are you sure you want to delete this blog?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if (count($blogs) > 0)
                        {{ $blogs->render('pagination::bootstrap-4') }}
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- ================ contact section end ================= -->

@endsection
