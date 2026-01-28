FROM node:20-alpine

WORKDIR /application/frontend

COPY /frontend/package.json /frontend/package-lock.json* ./
RUN npm install

COPY /frontend/ .

EXPOSE 3000
CMD ["npm", "run", "dev"]
