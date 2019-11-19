import { extend } from "flarum/extend";
import app from "flarum/app";
import CommentPost from "flarum/components/CommentPost";
import DiscussionPage from "flarum/components/DiscussionPage";
import LogInModal from "flarum/components/LogInModal";

app.initializers.add("irony/login-to-see", () => {
  extend(CommentPost.prototype, "config", function() {
    if (!app.session.user && app.current instanceof DiscussionPage)
      $(".login2see_login")
        .off("click")
        .on("click", () => app.modal.show(new LogInModal()));
  });
});
