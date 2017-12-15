let user = window.App.user;

module.exports = {
    updateReply(reply) {
        return reply.user_id == user.id;
    },

    updateAvatar(userProfile) {
        return userProfile.id == user.id;
    }
};
