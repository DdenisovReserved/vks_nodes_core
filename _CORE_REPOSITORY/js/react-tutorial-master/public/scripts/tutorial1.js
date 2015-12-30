var CommentBox = React.createClass({
    loadCommentsFromServer: function () {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function (data) {
                this.setState({data: data});
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    handleCommentSubmit: function(comment) {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: comment,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    getInitialState: function () {
        return {data: []};
    },
    componentWillMount: function () {
        this.loadCommentsFromServer();
        setInterval(this.loadCommentsFromServer, this.props.pollInterval);
    },
    render: function () {
        return (
            <div className="commentBox">
                <h2>Комменты</h2>
                <hr/>
                <CommentList data={this.state.data}/>
                <CommentForm onCommentSubmit={this.handleCommentSubmit}/>
            </div>
        );
    }
});

var CommentList = React.createClass({
    render: function () {
        var commentNodes = this.props.data.map(function (comment) {
            return (
                <Comment author={comment.author} key={comment.id} time={comment.time}>
                    {comment.text}
                </Comment>
            );
        });
        return (
            <div className='commentList'>
                {commentNodes}
            </div>
        );
    }
});

var CommentForm = React.createClass({
    getInitialState: function () {
        return {author: '', text: ''};
    },
    handleAuthorChange: function (e) {
        this.setState({author: e.target.value})
    },
    handleTextChange: function (e) {
        this.setState({text: e.target.value})
    },
    handleSubmit: function(e) {
        e.preventDefault();
        var author = this.state.author.trim();
        var text = this.state.text.trim();
        if(!text || !author) {
            return;
        }
        this.props.onCommentSubmit({author: author, text: text});
        this.setState({author: '', text: ''});
    },
    render: function () {
        return (

            <form className="commentForm form form-horizontal" onSubmit={this.handleSubmit}>
                <hr/>
                <div className='form-group'>
                    <h4>Ваш комментарий</h4>
                </div>
                <div className='form-group'>
                    <input type="text" className='form-control'
                           value={this.state.author}
                           placeholder="Your name"
                           onChange={this.handleAuthorChange}
                        />
                </div>
                <div className='form-group'>
                    <textarea type="text"
                              className='form-control'
                              placeholder="Say something..."
                              rows='5'
                              value={this.state.text}
                              onChange={this.handleTextChange}></textarea>
                </div>
                <div className='form-group'>
                    <input type="submit" className='btn btn-default' value="Отправить"/>
                </div>
            </form>
        );
    }
});

var Comment = React.createClass({
    rawMarkup: function () {
        var rawMarkup = marked(this.props.children.toString(), {sanitize: true});
        return {__html: rawMarkup}
    },

    render: function () {
        return (

            <div className='comment'>
                <h3 className='commentAuthor'>{this.props.author}</h3>
                <span dangerouslySetInnerHTML={this.rawMarkup()}/>
                <span className='pull-right text-muted'>{this.props.time}</span>
                <div className='clearfix'></div><hr/>
            </div>

        );
    }
});


ReactDOM.render(
    <CommentBox url="?route=test/apiComments" pollInterval={2000}/>,
    document.getElementById('content')
)
