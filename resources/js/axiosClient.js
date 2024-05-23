import axios from "axios";

const instance = axios.create();

instance.interceptors.request.use(function (config) {
    // TODO
    // Do something before request is sent
    return config;
});

export default instance;
